<?php
trait PangiationHandler {
    protected Pagination $pagination;
    
    protected function renderPagination(): void {
        $this->pagination->show();
    }

    protected function setPage(int $page = 1): void {
        $this->pagination->setDisplayPage($page);
    }

    protected function getPage(): int {
        return $this->pagination->getDisplayPage();
    }
}
?>