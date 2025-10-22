<?php
require_once '../utility/HtmlBuilder.php';

class Item {
    private int $id;
    private float $price;
    private string $name,$imagePath;
    private ?string $description = null;

    
    // PUBLIC
    public function __construct(array $item) {
        $this->id = $item['id'];
        $this->name = $item['name'];
        if(!empty($item['description'])) $this->description = $item['description'];
        $this->price = $item['price'];
        $this->imagePath = $item['image_path'];
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
}