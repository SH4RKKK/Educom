<?php
require_once '../abstract/Menu.php';

class MainMenu extends Menu {
    protected function initialize(): void {
        $this->menuItems = ['HOME','ABOUT','CONTACT','WEBSHOP'];
        $this->menuClass = 'options';
    }

    public final function updateMenu(): void {
        if (!empty($_SESSION['logged_in'])) {
            $this->menuItems[] = 'CART';
            $this->menuItems[] = 'LOGOUT';
        } else {
            $this->menuItems[] = 'LOGIN';
            $this->menuItems[] = 'REGISTER';
        }
    }
    
    protected function renderMenuItem(string $item): void {
        $this->openListItem();
        HtmlBuilder::openLink($item);
        
        echo $item;
        if (!empty($_SESSION['logged_in']) && $item === 'LOGOUT') echo ' ' . HtmlBuilder::escape($_SESSION['username']);
        
        HtmlBuilder::closeLink();
        $this->closeListItem();
    }
}