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

$itemsResult = $itemModel->getItems();
if ($itemsResult['success']) {
    $items = $itemsResult['items'];
    $cartItems = $shopModel->getCartItems($items);
    echo 'Cart has ' . count($cartItems) . ' items<br>';
    foreach ($cartItems as $cartItem) {
        echo '- ' . $cartItem['item']->getName() . ' x ' . $cartItem['amount'] . ' (€' . $cartItem['item']->getPrice() . ' each)<br>';
    }
    
    // Test createOrder()
    echo '<h3>Create Order</h3>';
    $userId = 1; // Make sure this user exists
    $orderResult = $shopModel->createOrder($userId, $cartItems);
    
    if ($orderResult['success']) {
        echo 'Success: ' . $orderResult['message'] . '<br>';
        echo 'Order ID: ' . $orderResult['orderId'] . '<br>';
        echo 'Total: €' . $orderResult['total'] . '<br>';
    } else {
        echo 'Error: ' . $orderResult['message'] . '<br>';
    }
    
    // Check cart after order
    $itemsResult2 = $itemModel->getItems();
    if ($itemsResult2['success']) {
        unset($_SESSION['orders']);
        echo 'Cart after order: ' . count($_SESSION['orders'] ?? []) . ' items<br>';
    }
} else {
    echo 'Error getting items: ' . $itemsResult['message'] . '<br>';
}