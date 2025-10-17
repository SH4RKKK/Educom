<?php
trait PangiationHandler {
    protected Pagination $pagination;
    
    protected function renderPagination(): void {
        $this->pagination->render();
    }
}
?>