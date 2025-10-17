<?php
require_once '../base/htmlPage.php';
require_once '../pages/home.php';

$home = new Home();

$page = new htmlPage(
    "Saman's Whey",
    'Saman Ahmad',
    '../css/style.css',
    'content',
    $home
);

$page->show();
?>