<?php

namespace controllers;

use models\Contact;
use models\Country;
use models\Guest;
use models\Status;
use models\User;
use models\Host;

class HostController extends Controller {

    public function addView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        return TemplateProvider::render('Host/add.twig', ['countries' => CountryController::getOptionList()] );

    }

    public function editView(string $id) : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $result = self::prepareData( Host::getSingle(['id' => $id, 'userID' => User::getUserID()]) );
        $result['iAgreeWithTerms'] = 1;

        return TemplateProvider::render('Host/add.twig',
            [ 'data' => self::json_enc($result), 'countries' => CountryController::getOptionList(), ]
        );

    }

    public function listView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $result = Host::get(['userID' => User::getUserID()]);

        if (count($result) === 0) {
            return $this->addView();
        }

        foreach ($result as &$vol) {
            $vol = self::decode(array_filter($vol));
            //echo var_export($vol,true);
            $vol['languages'] = Host::getLanguages($vol);
            $vol['country'] = Host::getCountry($vol);
            $vol['offer'] = Host::getOffer($vol);
        }

        return TemplateProvider::render('Host/list.twig',
            [ 'hosts' => $result, 'title' => 'Hosts list', 'edit' => true ]
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

        $success = isset($params['id']) ? Host::update($params) : Host::add($params);

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

        if (isset($params['email']) && filter_var($params['email'], FILTER_VALIDATE_EMAIL) === false) {
            $params['errors'][] = 'Invalid email';
        }

        if (empty($params["iAgreeWithTerms"])) {
            $params['errors'][] = "You are not agreed with disclaimer";
        } else {
            unset($params["iAgreeWithTerms"]);
        }

        return $params;

    }

    public static function getOptionList() : string {

        $list = Host::get( [ 'userID' => User::getUserID() ], ['id AS id', 'name as name'] );

        return TemplateProvider::render('Common/options.twig', [ 'options' => $list ] );

    }

    public function searchView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        return TemplateProvider::render('Host/search.twig');

    }

    public function search() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $params = $this->validate($_POST, 'search');

        $found = Host::get(array_merge($params, ['active' => 1]));

        foreach ($found as &$vol) {

            $vol = self::decode(array_filter($vol));
            $vol['languages'] = Host::getLanguages($vol);
            $vol['country'] = Host::getCountry($vol);
            $vol['offer'] = Host::getOffer($vol);

        }

        return TemplateProvider::render('Host/list.twig',
            [ 'hosts' => $found, 'title' => 'Search results' ]
        );

    }

    public function switchState(string $id) : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        Host::switchActiveState($id);
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

        $data = self::decode(array_filter(Host::getSingle([ 'id' => $id])));

        $data['languages'] = Host::getLanguages($data);
        $data['country'] = Host::getCountry($data);
        $data['offer'] = Host::getOffer($data);
        $data['contacts'] = Host::getContacts($data);

        $render = [
            'data' => $data,
            'choices' => GuestController::getOptionList(),
            'visit' => $visit,
        ];

        return TemplateProvider::render('Host/preview.twig', $render);

    }

    public function contact() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        if (empty($_POST['choice'])) {
            return Platform::error( 'Please select a guest to contact!' );
        }

        $id = $_POST['id'];
        $choiceID = $_POST['choice'];

        $result = Contact::get([ 'type' => Host::CONTACT_TYPE, 'toID' => $id, 'fromID' => $choiceID]);
        if (count($result) > 0) {
            return Platform::error( 'You have already tried this contact!' );
        }

        $toData = self::decode(array_filter( Host::getSingle(['id' => $id]) ));
        $toData['country'] = Host::getCountry($toData);
        $fromData = self::decode(array_filter(Guest::getSingle(['id' => $choiceID, 'userID' => User::getUserID()])));
        $fromData['country'] = Guest::getCountry($fromData);

        $params = [
            'type' => Host::CONTACT_TYPE,
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

        $mailText = TemplateProvider::render('Mail/hostContact.twig',['from' => $fromData, 'key' => $params['key'], 'to' => $toData]);
        $mailSent = Mailer::send($toData['email'],$toData['name'], 'Contact from guest', $mailText);

        $params = [
            'status' => $mailSent ? Status::STATUS_MAIL_SENT : Status::STATUS_MAIL_FAILED,
            'key' => $params['key'],
            'editDate' => (new \DateTime())->format('Y-m-d H:i:s'),
        ];
        $success = Contact::update($params);

        $resultMessage = $mailSent && $success
            ? "An e-mail has been sent to this host, with your contact details. 
            If they are interested in cooperation, they will contact you."
            : 'Email to host unexpectedly failed.';

        return TemplateProvider::render('Common/contact.twig', ['result' => $resultMessage] );

    }

    public function visitView(string $key) : string {

        $contact = Contact::getSingle([ 'type' => Host::CONTACT_TYPE, 'key' => $key]);

        $controller = new GuestController();
        return $controller->previewView((string)$contact['toID'], true);

    }

}