<?php
require_once '../abstract/Menu.php';

class MainMenu extends Menu {
    
    protected function initialize(): void {
        $this->menuItems = [
            'HOME',
            'ABOUT',
            'CONTACT',
            'WEBSHOP',
        ];

        $this->menuClass = 'options';
    }

    public function updateMenu(): void {
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
        
        if (!empty($_SESSION['logged_in']) && $item === 'LOGOUT') {
            echo $item . ' ' . HtmlBuilder::escape($_SESSION['username']);
        } else {
            echo $item;
        }
        
        HtmlBuilder::closeLink();
        $this->closeListItem();
    }
}