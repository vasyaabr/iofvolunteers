<?php

namespace controllers;

use models\Project;
use models\Status;
use models\Volunteer;
use models\User;
use models\Contact;

class VolunteerController extends Controller {

    private static $actions = ['register', 'search', 'list'];
    private const MAX_FILE_SIZE = 2097152;

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

    public function previewView(string $id, bool $visit = false) : string {

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
            'visit' => $visit,
        ];

        return TemplateProvider::render('Volunteer/preview.twig', $render);

    }

    public function contact() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        if (empty($_POST['project'])) {
            return Platform::error( 'Please select a project for contact!' );
        }

        $id = $_POST['id'];
        $projectID = $_POST['project'];

        $result = Contact::get(['type' => Volunteer::CONTACT_TYPE, 'toID' => $id, 'fromID' => $projectID]);
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
            'type' => Volunteer::CONTACT_TYPE,
            'toID' => $id,
            'fromID' => $projectID,
            'key' => md5(uniqid($id, true)),
            'status' => Status::STATUS_NEW,
            'authorID' => User::getUserID(),
        ];
        $success = Contact::add($params);

        if (!$success) {
            return Platform::error( 'Error in creating inivitation!' );
        }

        $mailText = TemplateProvider::render('Mail/invitation.twig',['volunteer' => $vData, 'key' => $params['key'], 'project' => $pData]);
        $mailSent = Mailer::send($vData['email'],$vData['name'], 'Invitation to orienteering project', $mailText);

        $params = [
            'status' => $mailSent ? Status::STATUS_MAIL_SENT : Status::STATUS_MAIL_FAILED,
            'key' => $params['key'],
            'editDate' => (new \DateTime())->format('Y-m-d H:i:s'),
        ];
        $success = Contact::update($params);

        $resultMessage = $mailSent && $success
            ? "An e-mail has been sent to this volunteer, with your contact details. 
            If they are interested in cooperation, they will contact you."
            : 'Email to volunteer unexpectedly failed';

        return TemplateProvider::render('Common/contact.twig', ['result' => $resultMessage] );

    }

    public function contactAll() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        if (empty($_POST['project'])) {
            return Platform::error( 'Please select a project for contact!' );
        }

        $projectID = $_POST['project'];
        $list = explode(',',$_POST['list']);
        if (empty($list)) {
            return Platform::error( 'No volunteers found!' );
        }

        $successCounter = 0;

        foreach ($list AS $id) {

            $result = Contact::get( [ 'type' => Volunteer::CONTACT_TYPE, 'toID' => $id, 'fromID' => $projectID ] );
            if ( count( $result ) > 0 ) {
                continue;
            }

            $vData = self::decode( array_filter( Volunteer::getSingle( [ 'id' => $id ] ) ) );

            if ( ! empty( $vData['excluded'] ) && $vData['excluded'] != 0 ) {
                continue;
            }

            $result = Project::getSingle( [ 'id' => $projectID, 'userID' => User::getUserID() ] );
            $pData  = self::decode( array_filter( $result ) );

            $params  = [
                'type'     => Volunteer::CONTACT_TYPE,
                'toID'     => $id,
                'fromID'   => $projectID,
                'key'      => md5( uniqid( $id, true ) ),
                'status'   => Status::STATUS_NEW,
                'authorID' => User::getUserID(),
            ];
            $success = Contact::add( $params );

            if ( ! $success ) {
                continue;
            }

            $mailText = TemplateProvider::render( 'Mail/invitation.twig', [
                'volunteer' => $vData,
                'key'       => $params['key'],
                'project'   => $pData
            ] );
            $mailSent = Mailer::send( $vData['email'], $vData['name'], 'Invitation to orienteering project', $mailText );

            $params  = [
                'status'   => $mailSent ? Status::STATUS_MAIL_SENT : Status::STATUS_MAIL_FAILED,
                'key'      => $params['key'],
                'editDate' => ( new \DateTime() )->format( 'Y-m-d H:i:s' ),
            ];
            $success = Contact::update( $params );
            $successCounter += $success ? 1 : 0;
        }

        $resultMessage = "{$successCounter} e-mails has been sent to found volunteers, with your contact details. 
            If they are interested in cooperation, they will contact you.";

        return TemplateProvider::render('Common/contact.twig', ['result' => $resultMessage] );

    }

    public function add() : string {

        if((int)$_SERVER['CONTENT_LENGTH']>0 && count($_POST)===0){
            return self::error('Files are too big');
        }

        if (!User::isAuthenticated()) {
            return self::error('You are not authenticated');
        }

        //error_log(var_export($_POST,true));
        $validationErrors = Volunteer::validateAll($_POST, true);
        if (!empty($validationErrors)) {
            return self::error(implode("<br>",$validationErrors));
        }

        // filter and set additional fields
        $params = array_map(
            function ($v) { return is_array($v) ? self::json_enc($v) : $v; },
            self::array_filter_recursive($_POST)
        );

        $params['userID'] = User::getUserID();

        unset($params["iAgreeWithTerms"]);
        unset($params['MAX_FILE_SIZE']);

        // fill tech fields
        if (isset($params['mappingDesc'])) { $params['mappingSkilled'] = 1; }
        if (isset($params['coachDesc'])) { $params['coachSkilled'] = 1; }
        if (isset($params['itDesc'])) { $params['itSkilled'] = 1; }
        if (isset($params['eventDesc'])) { $params['eventSkilled'] = 1; }
        if (isset($params['teacherDesc'])) { $params['teacherSkilled'] = 1; }

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
            return self::error(implode("<br>",$params['errors']));
        }

        if (!empty($files)) {
            $params['maps'] = self::json_enc($files);
        }

        // modify database
        $success = isset($params['id']) ? Volunteer::update($params) : Volunteer::add($params);

        return $success ? self::json_enc( Volunteer::getSingle(self::decode($params)) ) : self::error('Volunteer already saved');

    }

    public function search() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        // filter empty values and arrays
        $params = self::array_filter_recursive($_POST);

        $validationErrors = Volunteer::validateAll($params);
        if (!empty($validationErrors)) {
            return Platform::error( implode("<br>",$validationErrors) );
        }

        $found = Volunteer::get(array_merge($params, ['active' => 1]));

        foreach ($found as &$vol) {

            $vol = self::decode(array_filter($vol));

            $vol['languages'] = Volunteer::getLanguages($vol);
            $vol['competitorExp'] = Volunteer::getCompetitorExp($vol);

        }

        return TemplateProvider::render('Volunteer/list.twig',
            [
                'volunteers' => $found,
                'title' => 'Search results',
                'list' => implode(',',array_column($found, 'id')),
                'projects' => ProjectController::getOptionList(),
            ]
        );

    }

    public function visitView(string $key) : string {

        $contact = Contact::getSingle([ 'type' => Volunteer::CONTACT_TYPE, 'key' => $key]);

        $controller = new ProjectController();
        return $controller->previewView((string)$contact['fromID'], true);

    }

    public function excludeView(string $key) : string {

        $invitation = Contact::getSingle([ 'key' => $key]);
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

    public static function getOptionList() : string {

        $list = Volunteer::get( [ 'userID' => User::getUserID() ], ['id AS id', 'name AS name'] );

        return TemplateProvider::render('Common/options.twig', [ 'options' => $list ] );

    }

}