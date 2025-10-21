<?php
require_once '../traits/getbutton.php';

class CartSummary {
    use GetButton;
    private array $summary;
    private string $class,$title;

    public function __construct(float $total, float $shipping) {
        $this->class = 'cart-summary';
        $this->title =  'Totalen';

        $this->summary = [
            ['label' => 'Subtotaal', 'amount' => $total, 'class' => 'summary-row'],
            ['label' => 'Shipping', 'amount' => $shipping, 'class' => 'summary-row'],
            ['label' => 'Totaal', 'amount' => $total + $shipping, 'class' => 'summary-total']
        ];

        $this->btnPage = 'checkout';
        $this->btnClass = 'checkout-btn';
        $this->btnMsg = 'Afrekenen';
    }

    public final function renderSummary(): void {
        HtmlBuilder::openDiv($this->class);
        HtmlBuilder::showTitle($this->title);
        $this->renderSummarySection($this->summary);
        $this->renderGetButton();
        HtmlBuilder::closeDiv();
    }

    private function renderSummarySection(array $rows): void {
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