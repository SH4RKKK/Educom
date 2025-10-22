<?php
require_once '../base/HtmlPage.php';
require_once '../pages/Cart.php';
$test = $_GET['test'] ?? 1;
session_start();

$items = [
    new Item([
        'id' => 1,
        'name' => 'Product 1',
        'price' => 19.99,
        'description' => 'Dit is een geweldig product',
        'image_path' => '../images/1.avif',
        'amount' => rand(1, 5)
    ]),
    new Item([
        'id' => 2,
        'name' => 'Product 2',
        'price' => 29.99,
        'description' => 'Dit is een nog beter product',
        'image_path' => '../images/2.avif',
        'amount' => rand(1, 5)
    ]),
];

switch ($test) {
    case 1: 
        $webshop = new Cart($items);
        break;
    case 2:
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = 'Piet';  

        $webshop = new Cart($items);
        break;
    case 3:
        $productsperpage = 10;

        $webshop = new Cart($items);
        break;
    case 4:
        $items = [];
        $webshop = new Cart($items);
        break;
}

$page = new HtmlPage(
    "Saman's Whey",
    'Saman Ahmad',
    '../css/style.css',
    'content',
    $webshop
);

$page->show();
session_destroy();
?>