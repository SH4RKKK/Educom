<?php
require_once '../abstract/Model.php';
require_once '../base/Item.php';

class ItemModel extends Model {
    public function getItems(bool $includeDescription = false): array {
        try {
            $columns = $includeDescription 
                ? "id, name, price, image_path, description" 
                : "id, name, price, image_path";
            
            $query = "SELECT $columns FROM items ORDER BY id ASC";
            $result = $this->database->query($query);
            
            $items = [];
            foreach ($result ?? [] as $itemData) {
                $items[] = new Item($itemData);
            }
            
            return [
                'success' => true,
                'items' => $items
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error getting items: ' . $e->getMessage(),
                'items' => []
            ];
        }
    }

    public function getItemById(int $id): array {
        try {
            $query = "SELECT id, name, price, image_path, description 
                      FROM items 
                      WHERE id = :id";
            $params = ['id' => $id];
            $result = $this->database->query($query, $params);
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'item' => null
                ];
            }
            
            return [
                'success' => true,
                'item' => new Item($result[0])
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error getting item: ' . $e->getMessage(),
                'item' => null
            ];
        }
    }
}