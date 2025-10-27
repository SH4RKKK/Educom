<?php
require_once '../abstract/Model.php';
require_once '../base/Item.php';

class ItemModel extends Model {
    public function getItems(bool $includeDescription = false, bool $includeRatings = false): array {
        try {
            $columns = $includeDescription 
                ? "i.id, i.name, i.price, i.image_path, i.description" 
                : "i.id, i.name, i.price, i.image_path";
            
            if ($includeRatings) {
                $query = "SELECT $columns,
                            CAST(AVG(ir.rating) AS DECIMAL(10,1)) AS avg_rating,
                            COUNT(ir.rating) AS rating_count
                          FROM items i
                          LEFT JOIN item_ratings ir ON i.id = ir.item_id
                          GROUP BY i.id, i.name, i.price, i.image_path" . 
                          ($includeDescription ? ", i.description" : "") . "
                          ORDER BY i.id ASC";
            } else {
                $query = "SELECT $columns FROM items i ORDER BY i.id ASC";
            }
            
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

    public function getItemById(int $id, bool $includeDescription = false, bool $includeRatings = false): array {
        try {
            $columns = $includeDescription 
                ? "i.id, i.name, i.price, i.image_path, i.description" 
                : "i.id, i.name, i.price, i.image_path";
            
            if ($includeRatings) {
                $query = "SELECT $columns,
                            CAST(AVG(ir.rating) AS DECIMAL(10,1)) AS avg_rating,
                            COUNT(ir.rating) AS rating_count
                          FROM items i
                          LEFT JOIN item_ratings ir ON i.id = ir.item_id
                          WHERE i.id = :id
                          GROUP BY i.id, i.name, i.price, i.image_path" . 
                          ($includeDescription ? ", i.description" : "");
            } else {
                $query = "SELECT $columns FROM items i WHERE i.id = :id";
            }
            
            $params = ['id' => $id];
            $result = $this->database->query($query, $params);
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Product niet gevonden',
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

    public final function insertRating(int $rating, int $userId, int $item_id): bool {
        $query = "INSERT INTO item_ratings (rating, user_id, item_id) VALUES (:rating, :user_id, :item_id)";
        $params = [
            'rating' => $rating,
            'user_id' => $userId,
            'item_id' => $item_id
        ];
        $this->database->query($query, $params);
        return true;
    }

    public final function hasUserRatedItem(int $userId, int $itemId): bool {
        $query = "SELECT COUNT(*) as count FROM item_ratings WHERE user_id = :user_id AND item_id = :item_id";
        $params = [
            'user_id' => $userId,
            'item_id' => $itemId
        ];
        $result = $this->database->query($query, $params);
        
        return !empty($result) && $result[0]['count'] > 0;
    }

    public final function hasUserOrderedItem(int $userId, int $itemId): bool {
        $query = "SELECT COUNT(*) as count 
                  FROM order_items oi
                  JOIN orders o ON oi.order_id = o.id
                  WHERE o.user_id = :user_id AND oi.item_id = :item_id";
        $params = [
            'user_id' => $userId,
            'item_id' => $itemId
        ];
        $result = $this->database->query($query, $params);
        
        return !empty($result) && $result[0]['count'] > 0;
    }
}