<?php

namespace controllers;

use models\Contact;
use models\Status;
use models\User;
use models\Project;
use models\Volunteer;

class ProjectController extends Controller {

    public function addView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        return TemplateProvider::render('Project/add.twig', ['countries' => CountryController::getOptionList()]);

    }

    public function editView(string $id) : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $result = self::prepareData( Project::getSingle(['id' => $id, 'userID' => User::getUserID()]) );
        $result['iAgreeWithTerms'] = 1;

        return TemplateProvider::render('Project/add.twig',
            [ 'data' => self::json_enc($result), 'countries' => CountryController::getOptionList(), ]
        );

    }

    public function listView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $result = Project::get(['userID' => User::getUserID()]);

        if (count($result) === 0) {
            return $this->addView();
        }

        foreach ($result as &$vol) {
            $vol = self::decode(array_filter($vol));
            $vol['offer'] = Project::getOffer($vol);
        }

        return TemplateProvider::render('Project/list.twig',
            [ 'projects' => $result, 'title' => 'Projects list', 'edit' => true ]
        );

    }

    public function add() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $params = $this->validate($_POST, 'register');

        // show validation errors
        if (!empty($params['errors'])) {
            return Platform::error( $params['errors'] );
        }

        $success = isset($params['id']) ? Project::update($params) : Project::add($params);

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

        if (empty($params["iAgreeWithTerms"])) {
            $params['errors'][] = "You are not agreed with disclaimer";
        } else {
            unset($params["iAgreeWithTerms"]);
        }

        return $params;

    }

    public static function getOptionList() : string {

        $list = Project::get( [ 'userID' => User::getUserID() ], ['id AS id', 'concat(name," - ",place) as name'] );

        return TemplateProvider::render('Common/options.twig', [ 'options' => $list ] );

    }

    public function searchView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        return TemplateProvider::render('Project/search.twig');

    }

    public function search() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $params = $this->validate($_POST, 'search');

        $found = Project::get(array_merge($params, ['active' => 1]));

        foreach ($found as &$vol) {

            $vol = self::decode(array_filter($vol));
            $vol['offer'] = Project::getOffer($vol);

        }

        return TemplateProvider::render('Project/list.twig',
            [ 'projects' => $found, 'title' => 'Project search results' ]
        );

    }

    public function switchState(string $id) : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        Project::switchActiveState($id);
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();

    }

    /**
     * Contacts section
     */

    public function previewView(string $id, bool $visit = false) : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $data = self::decode(array_filter(Project::getSingle([ 'id' => $id])));
        $data['offer'] = Project::getOffer($data);
        $data['expirience'] = Project::getExpirience($data);

        $render = [
            'data' => $data,
            'choices' => VolunteerController::getOptionList(),
            'visit' => $visit,
        ];

        return TemplateProvider::render('Project/preview.twig', $render);

    }

    public function contact() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        if (empty($_POST['choice'])) {
            return Platform::error( 'Please select a volunteer to contact!' );
        }

        $id = $_POST['id'];
        $choiceID = $_POST['choice'];

        $result = Contact::get([ 'type' => Project::CONTACT_TYPE, 'toID' => $id, 'fromID' => $choiceID]);
        if (count($result) > 0) {
            return Platform::error( 'You have already tried this contact!' );
        }

        $toData = self::decode(array_filter( Project::getSingle(['id' => $id]) ));
        $toData['country'] = Project::getCountry($toData);
        $fromData = self::decode(array_filter(Volunteer::getSingle(['id' => $choiceID, 'userID' => User::getUserID()])));
        $fromData['country'] = Volunteer::getCountry($fromData);

        $params = [
            'type' => Project::CONTACT_TYPE,
            'toID' => $id,
            'fromID' => $choiceID,
            'key' => md5(uniqid($id, true)),
            'status' => Status::STATUS_NEW,
            'authorID' => User::getUserID(),
        ];
        $success = Contact::add($params);

        if (!$success) {
            return Platform::error( 'Error in contact!' );
        }

        $mailText = TemplateProvider::render('Mail/projectContact.twig',['from' => $fromData, 'key' => $params['key'], 'to' => $toData]);
        $mailSent = Mailer::send($toData['email'],$toData['name'], 'Contact from volunteer', $mailText);

        $params = [
            'status' => $mailSent ? Status::STATUS_MAIL_SENT : Status::STATUS_MAIL_FAILED,
            'key' => $params['key'],
            'editDate' => (new \DateTime())->format('Y-m-d H:i:s'),
        ];
        $success = Contact::update($params);

        $resultMessage = $mailSent && $success
            ? "An e-mail has been sent to this project leader, with your contact details. 
            If they are interested in cooperation, they will contact you."
            : 'Email to project leader unexpectedly failed.';

        return TemplateProvider::render('Common/contact.twig', ['result' => $resultMessage] );

    }

    public function visitView(string $key) : string {

        $contact = Contact::getSingle([ 'type' => Project::CONTACT_TYPE, 'key' => $key]);

        $controller = new VolunteerController();
        return $controller->previewView((string)$contact['toID'], true);

    }

}