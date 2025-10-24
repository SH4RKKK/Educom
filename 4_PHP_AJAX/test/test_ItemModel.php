<?php
require_once '../database/Database.php';
require_once '../abstract/Model.php';
require_once '../models/ItemModel.php';
require_once '../base/Item.php';

$database = new Database('localhost', 'website', 'root', '');
$itemModel = new ItemModel($database);

echo '<h2>ItemModel Tests</h2>';

// Test getItems()
echo '<h3>Get Items</h3>';
$result = $itemModel->getItems(true);

if ($result['success']) {
    echo 'Success: Found ' . count($result['items']) . ' item objects<br>';
    foreach ($result['items'] as $item) {
        echo '- ' . $item->getName() . ' (€' . $item->getPrice() . ')<br>';
    }
} else {
    echo 'Error: ' . $result['message'] . '<br>';
}

// Test getItemById()
echo '<h3>Get Item by ID (id = 1)</h3>';
$result = $itemModel->getItemById(1);

if ($result['success']) {
    $item = $result['item'];
    echo 'Success: Found ' . $item->getName() . ' - €' . $item->getPrice() . ' ID: ' . $item->getId() . '<br>';
} else {
    echo 'Error: ' . $result['message'] . '<br>';
}

// Test getItemById() with non-existent ID
echo '<h3>Get Item by ID (id = 999)</h3>';
$result = $itemModel->getItemById(999);

if ($result['success']) {
    echo 'Success: Found item<br>';
} else {
    echo 'Expected error: ' . $result['message'] . '<br>';
}