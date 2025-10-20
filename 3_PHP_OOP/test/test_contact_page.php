<?php
require_once '../base/htmlPage.php';
require_once '../pages/contact.php';

$test = $_GET['test'] ?? 1;

switch ($test) {
    case 1:
    $contact = new Contact();
    break;

    case 2: //missing field
        $fakePostData = [
            'naam' => 'Some Body',
            'email' => '',
            'bericht' => 'Dit is een test bericht!'
        ];
        
        $_POST = $fakePostData;

        $contact = new Contact();
    break;

    case 3: //all fields filled
        $fakePostData = [
            'naam' => 'Some Body',
            'email' => 'some@body.com',
            'bericht' => 'Dit is een test bericht!'
        ];
        
        $_POST = $fakePostData;
        $contact = new Contact();
}

$page = new htmlPage(
    "Saman's Whey",
    'Saman Ahmad',
    '../css/style.css',
    'content',
    $contact
);

$page->show();
?>