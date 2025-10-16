<?php
require_once 'stringhelper.php';

class HtmlBuilder {
    
    // BASIC HTML ELEMENTS
    public static function openDiv(string $class = ''): void {
        echo '<div' . ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>';
    }
    
    public static function closeDiv(): void {
        echo '</div>';
    }

    // TEXT
    public static function showTitle(string $title, string $class = ''): void {
        echo '<h1' . ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>' . 
             StringHelper::escape($title) . '</h1>';
    }
    
    public static function showMessage(string $message, string $class = ''): void {
        echo '<p' . ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>' . 
             StringHelper::escape($message) . '</p>';
    }
    
    public static function showSpan(string $msg, string $class = ''): void {
        echo '<span' . ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>' . 
             StringHelper::escape($msg) . '</span>';
    }
    
    public static function newLine(): void {
        echo '<br>';
    }
    
    // LINK
    public static function openLink(string $page, string $class = ''): void {
        echo '<a href="index.php?page=' . strtolower(StringHelper::escape($page)) . '"' . 
             ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>';
    }
    
    public static function closeLink(): void {
        echo '</a>';
    }
    
    // IMAGE
    public static function loadImage(string $path, string $name, string $class = ''): void {
        echo '<img src="images/' . StringHelper::escape($path) . '" alt="' . StringHelper::escape($name) . '"' . 
             ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>';
    }
}
?>