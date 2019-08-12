<?php

namespace controllers;

use models\Project;
use models\Volunteer;
use models\User;
use models\Invitation;

class VolunteerController extends Controller {

    private static $actions = ['register', 'search', 'list'];
    private const MAX_FILE_SIZE = 5242880;

    public function addView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        return TemplateProvider::render('Volunteer/add.twig',
            [
                'MAX_FILE_SIZE' => self::MAX_FILE_SIZE,
                'countries' => CountryController::getOptionList(),
            ]
        );

    }

    public function editView(string $id) : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $result = self::prepareData( Volunteer::getSingle(['id' => $id, 'userID' => User::getUserID()]) );
        $result['iAgreeWithTerms'] = 1;

        return TemplateProvider::render('Volunteer/add.twig',
            [
                'data' => self::json_enc($result),
                'MAX_FILE_SIZE' => self::MAX_FILE_SIZE,
                'maps' => Volunteer::getMapLinks($result),
                'countries' => CountryController::getOptionList(),
            ]
        );

    }

    public function listView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $result = Volunteer::get(['userID' => User::getUserID() ]);

        if (count($result) === 0) {
            return $this->addView();
        }

        foreach ($result as &$vol) {

            $vol = self::decode(array_filter($vol));

            $vol['languages'] = Volunteer::getLanguages($vol);
            $vol['competitorExp'] = Volunteer::getCompetitorExp($vol);

        }

        return TemplateProvider::render('Volunteer/list.twig',
            [ 'volunteers' => $result, 'title' => 'Volunteers list', 'edit' => true ]
        );

    }


    public function searchView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        if (empty(Project::get(['userID' => User::getUserID()]))) {
            return Platform::error( 'You have not any projects, please create one.' );
        }

        return TemplateProvider::render('Volunteer/search.twig');

    }

    public function previewView(string $id) : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $result = self::decode(array_filter(Volunteer::getPreview($id)));

        $result['languages'] = Volunteer::getLanguages($result);
        $result['competitorExp'] = Volunteer::getCompetitorExp($result);
        $result['preferredContinents'] = Volunteer::getPreferredContinents($result);
        $result['age'] = Volunteer::getAge($result);
        $result['skills'] = Volunteer::getSkills($result);

        $render = [
            'data' => $result,
            'projects' => ProjectController::getOptionList(),
            'maps' => Volunteer::getMapLinks($result),
        ];

        return TemplateProvider::render('Volunteer/preview.twig', $render);

    }

    public function contact() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        if (empty($_POST['project'])) {
            return Platform::error( 'Please select a project for invite!' );
        }

        $id = $_POST['id'];
        $projectID = $_POST['project'];

        $result = Invitation::get(['volunteerID' => $id, 'projectID' => $projectID]);
        if (count($result) > 0) {
            return Platform::error( 'Volunteer already contacted for this project!' );
        }

        $vData = self::decode(array_filter( Volunteer::getSingle(['id' => $id]) ));

        if (!empty($vData['excluded']) && $vData['excluded'] != 0) {
            return Platform::error( 'Volunteer asked to exclude him from contacts!' );
        }

        $result = Project::getSingle(['id' => $projectID, 'userID' => User::getUserID()]);
        $pData = self::decode(array_filter($result));

        $params = [
            'volunteerID' => $id,
            'projectID' => $projectID,
            'key' => md5(uniqid($id, true)),
            'status' => 'new',
            'authorID' => User::getUserID(),
        ];
        $success = Invitation::add($params);

        if (!$success) {
            return Platform::error( 'Error in creating inivitation!' );
        }

        $mailText = TemplateProvider::render('Mail/invitation.twig',['volunteer' => $vData, 'key' => $params['key'], 'project' => $pData]);
        $mailSent = Mailer::send($vData['email'],$vData['name'], 'Invitation to orienteering project', $mailText);

        $params = [
            'status' => $mailSent ? 'mail sent' : 'mail failed',
            'key' => $params['key'],
            'editDate' => (new \DateTime())->format('Y-m-d H:i:s'),
        ];
        $success = Invitation::update($params);

        return TemplateProvider::render('Volunteer/contact.twig',
            ['result' => 'Invitation to volunteer ' . ($mailSent && $success ? 'was sent' : 'unexpectedly failed')]
        );

    }

    public function add() : string {

        if((int)$_SERVER['CONTENT_LENGTH']>0 && count($_POST)===0){
            $params['errors'][] = "Files are too big";
            return Platform::error( $params['errors'] );
        }

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $params = $this->validate($_POST, 'register');

        // process uploaded maps
        $files = [];
        if (!empty($_FILES["maps"]) && empty($params['errors'])) {

            $uploads_dir = dirname(__DIR__) . '/upload/' . (new \DateTime())->format('Y/m');

            if (!file_exists($uploads_dir)) {
                !is_dir($uploads_dir) && !mkdir($uploads_dir, 0755, true) && !is_dir($uploads_dir);
            }

            foreach ($_FILES["maps"]["error"] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {

                    if ($_FILES["maps"]['size'][$key] > self::MAX_FILE_SIZE) {
                        $params['errors'][] = "File is too big {$_FILES["maps"]["name"][$key]}";
                        continue;
                    }

                    $tmp_name = $_FILES["maps"]["tmp_name"][$key];
                    $filename = uniqid('map-', true) . pathinfo($_FILES["maps"]["name"][$key], PATHINFO_EXTENSION);
                    $uploaded = move_uploaded_file($tmp_name, $uploads_dir . "/{$filename}");
                    if ($uploaded) {
                        $files[] = $uploads_dir . "/{$filename}";
                    }
                } else if ($error === UPLOAD_ERR_INI_SIZE) {
                    $params['errors'][] = "File `{$_FILES["maps"]["name"][$key]}` is too big";
                }
            }

        }

        // show files errors
        if (!empty($params['errors'])) {
            return Platform::error( $params['errors'] );
        }

        if (!empty($files)) {
            $params['maps'] = self::json_enc($files);
        }

        // modify database
        $success = isset($params['id']) ? Volunteer::update($params) : Volunteer::add($params);

        return $success ? $this->listView() : Platform::error( 'Unexpected error' );

    }

    /**
     * Validate params and set 'errors' element on errors
     *
     * @param array $params
     * @param string $action
     *
     * @return array
     * @throws \Exception
     */
    private function validate(array $params, string $action) : array {

        if ( empty($action) || !in_array($action,self::$actions,true) ) {
            throw new \Exception('Invalid validate action');
        }

        foreach ($params as $key => &$param) {

            // remove all empty array elements
            if (is_array($param)) {
                $param = array_filter($param);
            }

            // remove empty elements
            if (empty($param)) {
                unset($params[$key]);
            }

            // convert arrays to JSON
            if (is_array($param) && $action === 'register') {
                $param = self::json_enc($param);
            }

        }

        if ($action === 'search') {
            return $params;
        }

        $params['userID'] = User::getUserID();

        // check for required fields
        foreach (Volunteer::$requiredFields as $key) {
            if (!isset($params[$key])) {
                $params['errors'][] = "Required field `{$key}` is missing`";
            }
        }

        if (isset($params['email']) && filter_var($params['email'], FILTER_VALIDATE_EMAIL) === false) {
            $params['errors'][] = 'Invalid email';
        }

        if (isset($params['birthdate']) && !\DateTime::createFromFormat('Y-m-d',$params['birthdate'])) {
            $params['errors'][] = 'Invalid date of birth format (should be yyyy-mm-dd)';
        }

        if (empty($params["iAgreeWithTerms"])) {
            $params['errors'][] = "You are not agreed with disclaimer";
        } else {
            unset($params["iAgreeWithTerms"]);
        }
        unset($params['MAX_FILE_SIZE']);

        // fill tech fields
        if (isset($params['mappingDesc'])) { $params['mappingSkilled'] = 1; }
        if (isset($params['coachDesc'])) { $params['coachSkilled'] = 1; }
        if (isset($params['itDesc'])) { $params['itSkilled'] = 1; }
        if (isset($params['eventDesc'])) { $params['eventSkilled'] = 1; }
        if (isset($params['teacherDesc'])) { $params['teacherSkilled'] = 1; }

        return $params;

    }

    public function search() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $params = $this->validate($_POST, 'search');

        $found = Volunteer::get(array_merge($params, ['active' => 1]));

        foreach ($found as &$vol) {

            $vol = self::decode(array_filter($vol));

            $vol['languages'] = Volunteer::getLanguages($vol);
            $vol['competitorExp'] = Volunteer::getCompetitorExp($vol);

        }

        return TemplateProvider::render('Volunteer/list.twig',
            [ 'volunteers' => $found, 'title' => 'Search results' ]
        );

    }

    public function visitView(string $key) : string {

        $invitation = Invitation::getSingle(['key' => $key]);
        $project = self::decode(Project::getSingle( ['id' => $invitation['projectID']] ));

        $project['offer'] = Project::getOffer($project);

        return TemplateProvider::render('Project/preview.twig', [ 'data' => $project ]);

    }

    public function excludeView(string $key) : string {

        $invitation = Invitation::getSingle(['key' => $key]);
        Volunteer::update( ['id' => $invitation['volunteerID'], 'excluded' => 1] );

        return TemplateProvider::render('Volunteer/exclude.twig');

    }

    public function switchState(string $id) : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        Volunteer::switchActiveState($id);
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();

    }

}