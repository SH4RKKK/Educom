<?php
require_once '../abstract/Model.php';

class ShopModel extends Model {
    private string $error = '',$succesMsg = '';

    public function createOrder(int $userId, array $cartItems): void {
        try {
            $conn = $this->database->getConnection();
            $conn->beginTransaction();
            
            $total = 0;
            foreach ($cartItems as $cartItem) {
                $item = $cartItem['item'];
                $amount = $cartItem['amount'];
                $total += $item->getPrice() * $amount;
            }
            
            // Insert into orders table
            $query = "INSERT INTO orders (user_id, status_id, total) VALUES (:user_id, 1, :total)";
            $params = [
                'user_id' => $userId,
                'total' => $total
            ];
            $this->database->query($query, $params);
            
            $orderId = $conn->lastInsertId();
            
            // Insert into order_items table
            $query = "INSERT INTO order_items (order_id, item_id, amount, unit_price) VALUES (:order_id, :item_id, :amount, :unit_price)";
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
            
            $conn->commit();
            unset($_SESSION['orders']);
            $this->succesMsg = 'Order geplaatst! Order #' . $orderId;
            
        } catch (Throwable $e) {
            $conn->rollback();
            $this->error = 'Error adding order: ' . $e->getMessage();
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

    public function getError(): string {
        return $this->error;
    }

    public function getSuccesMsg(): string {
        return $this->succesMsg;
    }
}