<?php
class HtmlBuilder {
    
    // BASIC HTML ELEMENTS
    public static function openDiv(string $class = ''): void {
        echo '<div' . ($class ? ' class="' . self::escape($class) . '"' : '') . '>';
    }
    
    public static function closeDiv(): void {
        echo '</div>';
    }

    // TEXT
    public static function showTitle(string $title, string $class = ''): void {
        echo '<h1' . ($class ? ' class="' . self::escape($class) . '"' : '') . '>' . 
             self::escape($title) . '</h1>';
    }
    
    public static function showMessage(string $message, string $class = ''): void {
        echo '<p' . ($class ? ' class="' . self::escape($class) . '"' : '') . '>' . 
             self::escape($message) . '</p>';
    }
    
    public static function showSpan(string $msg, string $class = ''): void {
        echo '<span' . ($class ? ' class="' . self::escape($class) . '"' : '') . '>' . 
             self::escape($msg) . '</span>';
    }
    
    public static function newLine(): void {
        echo '<br>';
    }
    
    // LINK
    public static function openLink(string $page, string $class = '', array $params = []): void {
        $url = 'index.php?page=' . self::escape(strtolower($page));
        
        foreach ($params as $key => $value) {
            $url .= '&' . self::escape($key) . '=' . self::escape($value);
        }
        
        echo '<a href="' . $url . '"' . 
             ($class ? ' class="' . self::escape($class) . '"' : '') . '>';
    }
    
    public static function closeLink(): void {
        echo '</a>';
    }
    
    // IMAGE
    public static function loadImage(string $path, string $name, string $class = ''): void {
        echo '<img src="' . self::escape($path) . '" alt="' . self::escape($name) . '"' . 
             ($class ? ' class="' . self::escape($class) . '"' : '') . '>';
    }

    // UTIL
    public static function escape(string $text): string {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}
?>