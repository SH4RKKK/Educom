<?php
require_once '../base/HtmlPage.php';
require_once '../pages/Register.php';
$test = $_GET['test'] ?? 1;

switch ($test) {
    case 1:
        $register = new Register();
        break;

    case 2: //missing field
        $fakePostData = [
            'naam' => 'some body',
            'email' => 'some@body.com',
            'wachtwoord' => '',
            'herhaalwachtwoord' => '123'
        ];
        
        $_POST = $fakePostData;

        $register = new Register();
        break;

    case 3: //all fields filled
        $fakePostData = [
            'naam' => 'some body',
            'email' => 'some@body.com',
            'wachtwoord' => '123',
            'herhaalwachtwoord' => '123'
        ];
        
        $_POST = $fakePostData;

        $register = new Register();
        break;
    case 4: //mismatch passwords
        $fakePostData = [
            'naam' => 'some body',
            'email' => 'some@body.com',
            'wachtwoord' => '123',
            'herhaalwachtwoord' => '456'
        ];
        
        $_POST = $fakePostData;

        $register = new Register();
        break;
}

$page = new HtmlPage(
    "Saman's Whey",
    'Saman Ahmad',
    '../css/style.css',
    'content',
    $register
);

$page->show();
?>