<?php
require_once '../abstract/htmlPage.php';
require_once '../pages/contact.php';

$contact = new Contact();

$page = new htmlPage(
    "Saman's Whey",
    'Saman Ahmad',
    '../css/style.css',
    'content',
    $contact
);

$page->show();
?>