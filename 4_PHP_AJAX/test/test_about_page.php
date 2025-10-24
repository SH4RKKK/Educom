<?php
require_once '../base/HtmlPage.php';
require_once '../pages/About.php';

$about = new About();

$page = new HtmlPage(
    "Saman's Whey",
    'Saman Ahmad',
    '../css/style.css',
    'content',
    $about
);

$page->show();
?>