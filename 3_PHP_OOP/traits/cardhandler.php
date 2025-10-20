<?php
trait CardHandler {
    protected ItemCard $card;

    protected function renderCard(): void {
        $this->card->show();
    }
}
?>