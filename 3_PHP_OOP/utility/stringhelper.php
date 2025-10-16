<?php 
class StringHelper {
    public static function escape(string $text): string {
        return htmlspecialchars($text, ENT_QUOTES);
    }
    
    public static function slugify(string $text): string {
        $text = strtolower(trim($text));
        $text = str_replace([' ', '-', '_'], '', $text);
        $text = preg_replace('/[^a-z0-9]/', '', $text);
        return $text;
    }
}
?>