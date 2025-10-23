<?php
session_start();
require_once '../database/Database.php';
require_once '../abstract/Model.php';
require_once '../models/ShopModel.php';
require_once '../models/ItemModel.php';
require_once '../base/Item.php';

$database = new Database('localhost', 'website', 'root', '');
$shopModel = new ShopModel($database);
$itemModel = new ItemModel($database);

echo '<h2>ShopModel Tests</h2>';

// Test getCartItems()
echo '<h3>Get Cart Items</h3>';
$_SESSION['orders'] = [1 => 2, 2 => 1]; // ItemId 1: qty 2, ItemId 2: qty 1
$items = $itemModel->getItems();
$cartItems = $shopModel->getCartItems($items);
echo 'Cart has ' . count($cartItems) . ' items<br>';
foreach ($cartItems as $cartItem) {
    echo '- ' . $cartItem['item']->getName() . ' x ' . $cartItem['amount'] . ' (€' . $cartItem['item']->getPrice() . ' each)<br>';
}

// Test createOrder()
echo '<h3>Create Order</h3>';
$userId = 1; // Make sure this user exists
$shopModel->createOrder($userId, $cartItems);
echo ($shopModel->getError() ?: $shopModel->getSuccesMsg()) . '<br>';
echo 'Cart after order: ' . count($shopModel->getCartItems($items)) . ' items<br>';