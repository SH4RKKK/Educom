<?php
require_once '../forms/cardform.php';
require_once '../base/item.php';
require_once '../traits/getbutton.php';

abstract class ItemCard {
    use GetButton;
    protected ?CardForm $form = null;
    protected Item $item;
    
    public function __construct(Item $item) {
        $this->item = $item;
        $this->initialize();
    }

    private function makeCardForm(): void {
        !empty($_SESSION['logged_in']) ? $this->form = new CardForm($this->item->getId()) : $this->form = null;
    }

    public final function show(): void {
        $this->makeCardForm();
        $this->render();
    }

    abstract protected function initialize(): void;
    abstract protected function render(): void;
}
?>