<?php
require_once '../forms/CardForm.php';
require_once '../base/Item.php';
require_once '../base/ItemRating.php';
require_once '../traits/GetButton.php';

abstract class ItemCard {
    use GetButton;
    protected ?CardForm $form = null;
    protected Item $item;
    protected ItemRating $itemRating;
    protected string $cardClass,$cardContentClass,$cardActionClass;
    
    public function __construct(Item $item,ItemRating $itemrRating) {
        $this->item = $item;
        $this->itemRating = $itemrRating;
        $this->initialize();
    }

    private function makeCardForm(): void {
        !empty($_SESSION['logged_in']) ? $this->form = new CardForm($this->item->getId()) : $this->form = null;
    }

    public final function render(): void {
        $this->makeCardForm();
        $this->renderItemCard();
    }
    
    abstract protected function renderItemCard(): void;
    abstract protected function initialize(): void;
}