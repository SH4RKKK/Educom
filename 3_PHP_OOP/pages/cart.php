<?php
require_once '../abstract/body.php';

require_once '../traits/menuhandler.php';
require_once '../base/MainMenu.php';

require_once '../traits/title.php';

require_once '../base/item.php';

require_once '../table/carttable.php';
require_once '../traits/tablehandler.php';

class Cart extends BodyContent {
    use MenuHandler;
    use Title;
    use TableHandler;
    private CartTable $table;
    private $shipping;

    public function __construct(array $products) {
        //append amount to id before parsing
        $this->table = new CartTable($products);
        parent::__construct();
    }
    
    protected function initialize(): void {
        $this->title = [
            'text' => !empty($_SESSION['logged_in']) ? 'Hello ' . HtmlBuilder::escape($_SESSION['username']) : 'Hello Stranger',
            'class' => 'title'
        ];
        $this->menu = new MainMenu();
        $this->shipping = 5.95;
    }

    protected function render(): void {
        $this->renderTitle();
        $this->renderMenu();
        
        HtmlBuilder::openDiv('cart-wrapper');
        $this->title = ['text' => 'Winkel mandje'];
        $this->renderTitle();
        HtmlBuilder::openDiv('cart-items');
        
        $this->renderTable();
        HtmlBuilder::closeDiv();

        HtmlBuilder::openDiv('cart-summary');

        //Summary class?
        $this->title = ['text' => 'Totalen'];
        $this->renderTitle();

        $this->renderSummary([
            ['label' => 'Subtotaal', 'amount' => $this->table->getTotal(), 'class' => 'summary-row'],
            ['label' => 'Shipping', 'amount' => $this->shipping, 'class' => 'summary-row'],
            ['label' => 'Totaal', 'amount' => $this->table->getTotal() + $this->shipping, 'class' => 'summary-total']
        ]);

        HtmlBuilder::openLink('checkout', 'checkout-btn');
        echo 'Proceed to checkout';
        HtmlBuilder::closeLink();

        HtmlBuilder::closeDiv();
        HtmlBuilder::closeDiv();
        HtmlBuilder::closeDiv();
    }

    private function renderSummary(array $rows): void {
        foreach ($rows as $row) {
            $this->makeSummaryRow($row['label'], $row['amount'], $row['class'] ?? '');
        }
    }

    private function makeSummaryRow(string $label, float $amount, string $class = ''): void {
        HtmlBuilder::openDiv($class);
        HtmlBuilder::showSpan($label);
        HtmlBuilder::showSpan('€' . number_format($amount, 2, ',', '.'));
        HtmlBuilder::closeDiv();
    }
}
?>