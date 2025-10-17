<?php
class Pagination {
    private int $displayPage;
    private int $totalPages;
    private string $pageName;
    private string $class;
    private string $paraName;

    public function __construct(int $totalItems, int $productPerPage, string $pageName, string $class, string $paraName = 'index') {
        $this->totalPages = ceil($totalItems / $productPerPage);
        $this->pageName = $pageName;
        $this->class = $class;

        $this->paraName = $paraName;
        $this->setDisplayPage();
    }

    public function render(): void {
        if ($this->totalPages <= 1) {
            return;
        }
    
        HtmlBuilder::openDiv($this->class);
        for ($i = 1; $i <= $this->totalPages; $i++) {
            $activeClass = $i === $this->displayPage ? 'active' : '';
            
            HtmlBuilder::openLink($this->pageName, $activeClass, ['index' => $i]);
            echo $i;
            HtmlBuilder::closeLink();
        }
        HtmlBuilder::closeDiv();
    }

    public function getDisplayPage(): int {
        return $this->displayPage;
    }

    public function setDisplayPage(int $currentPage = 1) {
        $currentPage = isset($_GET[$this->paraName]) ? (int)$_GET[$this->paraName] : 1;
        $this->displayPage = max(1, min($currentPage, $this->totalPages));
    }
}
?>