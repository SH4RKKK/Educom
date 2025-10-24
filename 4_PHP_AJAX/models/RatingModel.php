<?php
class RatingModel extends Model {
    public final function fetchRating(int $id): array {
        $query = "SELECT CAST(AVG(rating) AS DECIMAL(10,1)) as avg_rating
                  FROM item_ratings
                  WHERE item_id = :item_id";
        $params = ['item_id' => $id];
        $result = $this->database->query($query, $params);
        
        if (empty($result) || $result[0]['avg_rating'] === null) {
            return [
                'success' => false,
                'message' => 'Product heeft geen rating'
            ];
        }
    
        return [
            'success' => true,
            'rating' => $result[0]['avg_rating']
        ];
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
                  JOIN orders o ON oi.order_id = o.order_id
                  WHERE o.user_id = :user_id AND oi.item_id = :item_id";
        $params = [
            'user_id' => $userId,
            'item_id' => $itemId
        ];
        $result = $this->database->query($query, $params);
        
        return !empty($result) && $result[0]['count'] > 0;
    }
}