<?php
require_once '../base/htmlPage.php';
require_once '../pages/product.php';
$test = $_GET['test'] ?? 1;
session_start();

$items = [
    new Item([
        'id' => 1,
        'name' => 'Product 1',
        'price' => 19.99,
        'description' => 'Dit is een geweldig product',
        'image_path' => '../images/1.avif'
    ]),
    new Item([
        'id' => 2,
        'name' => 'Product 2',
        'price' => 29.99,
        'description' => 'Dit is een nog beter product',
        'image_path' => '../images/2.avif'
    ]),
    new Item([
        'id' => 3,
        'name' => 'Product 3',
        'price' => 24.99,
        'description' => 'Perfect voor dagelijks gebruik',
        'image_path' => '../images/1.avif'
    ]),
    new Item([
        'id' => 4,
        'name' => 'Product 4',
        'price' => 34.99,
        'description' => 'Premium kwaliteit',
        'image_path' => '../images/2.avif'
    ]),
    new Item([
        'id' => 5,
        'name' => 'Product 5',
        'price' => 15.99,
        'description' => 'Beste prijs-kwaliteit verhouding',
        'image_path' => '../images/1.avif'
    ]),
    new Item([
        'id' => 6,
        'name' => 'Product 6',
        'price' => 44.99,
        'description' => 'Luxe uitvoering',
        'image_path' => '../images/2.avif'
    ]),
    new Item([
        'id' => 7,
        'name' => 'Product 7',
        'price' => 22.99,
        'description' => 'Populaire keuze',
        'image_path' => '../images/1.avif'
    ]),
    new Item([
        'id' => 8,
        'name' => 'Product 8',
        'price' => 39.99,
        'description' => 'Topkwaliteit gegarandeerd',
        'image_path' => '../images/2.avif'
    ]),
    new Item([
        'id' => 9,
        'name' => 'Product 9',
        'price' => 27.99,
        'description' => 'Uitstekende reviews',
        'image_path' => '../images/1.avif'
    ]),
    new Item([
        'id' => 10,
        'name' => 'Product 10',
        'price' => 49.99,
        'description' => 'Nieuwste model',
        'image_path' => '../images/2.avif'
    ])
];

$productsperpage = 2;

switch ($test) {
    case 1: 
        $webshop = new Product($items[1]);
        break;
    case 2:
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = 'Piet';  

        $webshop = new Product($items[1]);
        break;
    case 3:
        $items = []; //Controller logic missing to handle empty items,
        $webshop = new Product($items[1] ?? null);
        break;
}

$page = new htmlPage(
    "Saman's Whey",
    'Saman Ahmad',
    '../css/style.css',
    'content',
    $webshop
);

$page->show();
session_destroy();
?>