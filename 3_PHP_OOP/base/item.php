<?php
require_once '../utility/htmlelements.php';

class Item {
    private int $id,$amount;
    private float $price;
    private string $name,$description,$imagePath;
    
    // PUBLIC
    public function __construct(array $item) {
        $this->id = $item['id'];
        $this->name = $item['name'];
        $this->description = $item['description'];
        $this->price = $item['price'];
        $this->imagePath = $item['image_path'];
        $this->amount = $item['amount'] ?? 0;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getImagePath(): string {
        return $this->imagePath;
    }
    
    public function getAmount(): int {
        return $this->amount;
    }
}
?>