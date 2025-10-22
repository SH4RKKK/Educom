<?php
require_once '../base/HtmlPage.php';
require_once '../pages/Home.php';

$home = new Home();

$page = new HtmlPage(
    "Saman's Whey",
    'Saman Ahmad',
    '../css/style.css',
    'content',
    $home
);

$page->show();
?>