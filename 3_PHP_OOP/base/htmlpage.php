<?php 
require_once '../utility/HtmlBuilder.php';
require_once '../base/BodyContent.php';

class HtmlPage
{ 
    // PRIVATE
    private string $title,$author,$pathToCSS,$class;
    protected BodyContent $bodyContent;

    // PUBLIC
    public function __construct(string $title, string $author, string $pathToCSS, string $class = '', BodyContent $bodyContent)
    {
        $this->title = $title;
        $this->author = $author;
        $this->pathToCSS = $pathToCSS;
        $this->class = $class;
        $this->bodyContent = $bodyContent;
    }

    public final function show(): void 
    {
        $this->openHTML();
        $this->openHead();
        $this->showHeadContent();
        $this->closeHead();
        $this->openBody();
        HtmlBuilder::openDiv($this->class);
        $this->showBodyContent();
        HtmlBuilder::closeDiv();
        $this->openFooter();
        $this->showFooterContent();
        $this->closeFooter();
        $this->closeBody();
        $this->closeHTML();
    }  

    // PROTECTED
    protected function showHeadContent(): void
    { 
        if ($this->title) echo '<title>'.HtmlBuilder::escape($this->title).'</title>'; 
        if ($this->author) echo '<meta name="author" content="'.HtmlBuilder::escape($this->author).'" />'; 
        if ($this->pathToCSS) echo '<link rel="stylesheet" href="'.HtmlBuilder::escape($this->pathToCSS).'">';
    }

    protected function showFooterContent(): void
    { 
        echo '&copy ' . date("Y") . ' ' . HtmlBuilder::escape($this->author);
    }
    
    // PRIVATE
    private function showBodyContent(): void {
        $this->bodyContent->show();
    }

    private function openHTML(): void {
        echo '<!DOCTYPE html><html>'; 
    }

    private function closeHTML(): void {
        echo '</html>';
    }

    private function openBody(): void {
        echo '<body>';
    }

    private function closeBody(): void {
        echo '</body>';
    }

    private function openHead(): void {
        echo '<head>';
    }

    private function closeHead(): void {
        echo '</head>';
    }

    private function openFooter(): void {
        echo '<footer>';
    }

    private function closeFooter(): void {
        echo '</footer>';
    }
}