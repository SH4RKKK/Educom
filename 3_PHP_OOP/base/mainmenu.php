<?php
require_once '../abstract/menu.php';

class MainMenu extends Menu {
    
    protected function initialize(): void {
        $this->menuItems = [
            'HOME',
            'ABOUT',
            'CONTACT',
            'WEBSHOP',
        ];

        if (!empty($_SESSION['logged_in'])) {
            $this->menuItems[] = 'CART';
            $this->menuItems[] = 'LOGOUT';
        } else {
            $this->menuItems[] = 'LOGIN';
            $this->menuItems[] = 'REGISTER';
        }

        $this->menuClass = 'options';
    }
    
    protected function renderMenuItem(string $item): void {
        $this->openList();
        HtmlBuilder::openLink($item);
        
        if (!empty($_SESSION['logged_in']) && $item === 'LOGOUT') {
            echo $item . ' ' . HtmlBuilder::escape($_SESSION['username']);
        } else {
            echo $item;
        }
        
        HtmlBuilder::closeLink();
        $this->closeList();
    }
}
?>