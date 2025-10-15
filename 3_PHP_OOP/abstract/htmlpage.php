.<?php 
 abstract class htmlPage
 {
     private $title = 'Saman Whey';
     private $author = 'Saman Ahmad';
     
 // PUBLIC
     public function __construct(string $title, string $author)
     {
         $this->title = $title;
         $this->author = $author;
     }
 
     public function show()
     {
         $this->beginDoc();
         $this->beginHead();
         $this->showHeadContent();
         $this->endHead();
         $this->beginBody();
         $this->showBodyContent();
         $this->endBody();
         $this->endDoc();
     }  
       
 // PROTECTED
     protected function beginDoc(): void
     { 
         echo '<!DOCTYPE html>'.PHP_EOL.'<html>'; 
     }
 
     protected function showHeadContent(): void
     { 
         if ($this->title) 
             echo '<title>'.$this->title.'</title>'.PHP_EOL; 
         if ($this->author) 
             echo '<meta name="author" content="'.$this->author.'" />'.PHP_EOL; 
     }

// ABSTRACT METHOD - Children MUST implement
     abstract protected function showBodyContent(): void;

 //======================================================
 // PRIVATE
 //======================================================
     private function beginHead() 
     { 
         echo '<head>'.PHP_EOL; 
     }
 
     private function endHead()
     { 
         echo '</head>'.PHP_EOL; 
     }
     
     private function beginBody() 
     { 
         echo '<body>'.PHP_EOL; 
     }
 
     private function endBody() 
     { 
         echo '</body>'.PHP_EOL; 
     }
     
     private function endDoc() 
     { 
         echo '</html>'.PHP_EOL; 
     }
 }