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
$itemObjects = $itemModel->getItems(true);
echo 'Found ' . count($itemObjects) . ' item objects<br>';
foreach ($itemObjects as $item) {
    echo '- ' . $item->getName() . ' (€' . $item->getPrice() . ')<br>';
}

// Test getItemById()
echo '<h3>Get Item by ID (id = 1)</h3>';
$item = $itemModel->getItemById(1);
echo $item ? 'Found: ' . $item->getName() . ' - €' . $item->getPrice() . ' ID: ' . $item->getId() . '<br>' : 'Item not found<br>';