<?php
trait MenuHandler {
    protected Menu $menu;
    
    protected function renderMenu(): void {
        $this->menu->show();
    }
}
?>