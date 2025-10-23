<?php
class Pagination {
    private int $totalItems,$productPerPage,$displayPage,$totalPages;
    private string $pageName,$class,$paraName;

    public function __construct(int $totalItems, int $productPerPage, string $pageName, string $class, string $paraName = 'index') {
        $this->totalItems = $totalItems;
        $this->productPerPage = $productPerPage;
        $this->pageName = $pageName;
        $this->class = $class;
        $this->paraName = $paraName;
    }

    public function show(): void {
        $this->render();
    }

    private function render(): void {
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

    public final function getDisplayPage(): int {
        $this->setTotalPages();
        $this->setDisplayPage();
        return $this->displayPage;
    }

    private function setDisplayPage(int $currentPage = 1) {
        $currentPage = isset($_GET[$this->paraName]) ? (int)$_GET[$this->paraName] : 1;
        $this->displayPage = max(1, min($currentPage, $this->totalPages));
    }

    private function setTotalPages(): void {
        $this->totalPages = ceil($this->totalItems / $this->productPerPage);
    }
}