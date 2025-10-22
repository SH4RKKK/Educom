<?php
require_once '../base/HtmlPage.php';
require_once '../pages/Login.php';
$test = $_GET['test'] ?? 1;

switch ($test) {
    case 1:
    $login = new Login();
    break;

    case 2: //missing field
        $fakePostData = [
            'email' => 'some@body.com',
            'wachtwoord' => '',
        ];
        
        $_POST = $fakePostData;

        $login = new Login();
    break;

    case 3: //all fields filled
        $fakePostData = [
            'email' => 'some@body.com',
            'wachtwoord' => '123',
        ];
        
        $_POST = $fakePostData;

        $login = new Login();
}

$page = new HtmlPage(
    "Saman's Whey",
    'Saman Ahmad',
    '../css/style.css',
    'content',
    $login
);

$page->show();
?>