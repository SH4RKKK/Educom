<?php
require_once '../utility/HtmlBuilder.php';

class Item {
    private int $id;
    private float $price;
    private string $name,$imagePath;
    private ?string $description = null;
    private ?float $rating = null;
    private ?int $ratingCount = null;

    public function __construct(array $item) {
        $this->id = $item['id'];
        $this->name = $item['name'];
        if (!empty($item['description'])) $this->description = $item['description'];
        $this->price = $item['price'];
        $this->imagePath = $item['image_path'];
        $this->rating = $item['avg_rating'] ?? null;
        $this->ratingCount = $item['rating_count'] ?? null;
    }

    public final function getId(): int {
        return $this->id;
    }

    public final function getName(): string {
        return $this->name;
    }

    public final function getDescription(): ?string {
        return $this->description;
    }

    public final function getPrice(): float {
        return $this->price;
    }

    public final function getImagePath(): string {
        return $this->imagePath;
    }

    public final function getRating(): ?float {
        return $this->rating;
    }

    public final function getRatingCount(): ?int {
        return $this->ratingCount;
    }

    public final function hasRating(): bool {
        return $this->rating !== null;
    }
}