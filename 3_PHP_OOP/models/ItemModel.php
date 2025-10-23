<?php
require_once '../abstract/Model.php';
require_once '../base/Item.php';

class ItemModel extends Model {
    public function fetchItems(bool $includeDescription = false): array {
        $columns = 
            $includeDescription 
            ? "id, name, price, image_path, description" 
            : "id, name, price, image_path";
        
        $query = "SELECT $columns FROM items ORDER BY id ASC";
        $result = $this->database->query($query);
        return $result ?? [];
    }
    
    public function getItems(bool $includeDescription = false): array {
        try {
            $items = [];
            foreach ($this->fetchItems($includeDescription) as $itemData) {
                $items[] = new Item($itemData);
            }
            return $items;
        } catch (Exception $e) {
            return [];
        }
    }

    public function getItemById(int $id): ?Item {
        $query = "SELECT id, name, price, image_path, description FROM items WHERE id = :id";
        $params = ['id' => $id];
        $result = $this->database->query($query, $params);
        
        return !empty($result) ? new Item($result[0]) : null;
    }
}