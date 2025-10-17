<?php
require_once '../base/htmlPage.php';
require_once '../pages/webshop.php';
$test = $_GET['test'] ?? 1;

switch ($test) {
    case 1:
        $webshop = new Webshop();
        $page = new htmlPage(
            "Saman's Whey",
            'Saman Ahmad',
            '../css/style.css',
            'content',
            $webshop
        );

        $page->show();
        break;
}
?>