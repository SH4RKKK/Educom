<?php
require_once '../base/htmlPage.php';
require_once '../pages/about.php';

$about = new About();

$page = new htmlPage(
    "Saman's Whey",
    'Saman Ahmad',
    '../css/style.css',
    'content',
    $about
);

$page->show();
?>