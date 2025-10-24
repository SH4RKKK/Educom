<?php
require_once '../abstract/Model.php';

class ShopModel extends Model {
    public function createOrder(int $userId, array $cartItems): array {
        try {
            $conn = $this->database->getConnection();
            $conn->beginTransaction();
            
            $total = $this->calculateTotal($cartItems);
            $orderId = $this->insertOrder($userId, $total, $conn);
            $this->insertOrderLines($orderId, $cartItems);
            
            $conn->commit();
            
            return [
                'success' => true,
                'message' => 'Order geplaatst! Order #' . $orderId,
                'orderId' => $orderId,
                'total' => $total
            ];
            
        } catch (Throwable $e) {
            if (isset($conn)) {
                $conn->rollback();
            }
            
            return [
                'success' => false,
                'message' => 'Error adding order: ' . $e->getMessage()
            ];
        }
    }
    
    private function calculateTotal(array $cartItems): float {
        $total = 0;
        
        foreach ($cartItems as $cartItem) {
            $item = $cartItem['item'];
            $amount = $cartItem['amount'];
            $total += $item->getPrice() * $amount;
        }
        
        return $total;
    }

    private function insertOrder(int $userId, float $total, $conn): int {
        $query = "INSERT INTO orders (user_id, status_id, total) 
                  VALUES (:user_id, 1, :total)";
        $params = [
            'user_id' => $userId,
            'total' => $total
        ];
        
        $this->database->query($query, $params);
        
        return (int) $conn->lastInsertId();
    }

    private function insertOrderLines(int $orderId, array $cartItems): void {
        $query = "INSERT INTO order_items (order_id, item_id, amount, unit_price) 
                  VALUES (:order_id, :item_id, :amount, :unit_price)";
        
        foreach ($cartItems as $cartItem) {
            $item = $cartItem['item'];
            $amount = $cartItem['amount'];
            
            $params = [
                'order_id' => $orderId,
                'item_id' => $item->getId(),
                'amount' => $amount,
                'unit_price' => $item->getPrice()
            ];
            
            $this->database->query($query, $params);
        }
    }

    public function getCartItems(array $items): array {
        $orders = $_SESSION['orders'] ?? [];
        $cartItems = [];
        
        foreach ($items as $item) {
            if (isset($orders[$item->getId()])) {
                $cartItems[] = [
                    'item' => $item,
                    'amount' => $orders[$item->getId()]
                ];
            }
        }
        
        return $cartItems;
    }
}