<?php
require_once '../abstract/body.php';

require_once '../traits/menuhandler.php';
require_once '../base/MainMenu.php';

require_once '../traits/paginationhandler.php';
require_once '../base/pagination.php';

require_once '../traits/title.php';

class Webshop extends BodyContent {
    use MenuHandler;
    use PangiationHandler;
    use Title;
    
    protected function initialize(): void {
        $this->title = [
            'text' => !empty($_SESSION['logged_in']) ? 'Hello ' . HtmlBuilder::escape($_SESSION['username']) : 'Hello Stranger',
            'class' => 'title'
        ];

        $this->menu = new MainMenu();
        
        //Testin variables need to set them in init and store them
        $this->pagination = new Pagination(5,2,'webshop','pagination', 'index');
    }

    protected function render(): void {
        $this->renderTitle();
        $this->renderMenu();
        $this->renderPagination();
    }
}
?>