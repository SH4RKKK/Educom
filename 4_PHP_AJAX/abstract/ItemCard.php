<?php
require_once '../forms/CardForm.php';
require_once '../forms/RatingForm.php';
require_once '../base/Item.php';
require_once '../traits/GetButton.php';

abstract class ItemCard {
    use GetButton;
    protected ?CardForm $form = null;
    protected Item $item;
    protected bool $canRate;
    protected string $cardClass,$cardContentClass,$cardActionClass,/*rating stuff*/$ratingError,$noRatingMsg,$postRating,$postBtnClass,$message,$ratingId;
    
    public function __construct(Item $item, bool $canRate = false, string $ratingError = '', string $ratingMessage = '') {
        $this->item = $item;
        $this->canRate = $canRate;
        $this->ratingError = $ratingError;
        $this->noRatingMsg = 'Dit product heeft geen rating!';
        $this->postRating = 'rating';
        $this->postBtnClass = 'rating-btn';
        $this->message = $ratingMessage;
        $this->ratingId = 'rating';
        $this->initialize();
    }

    protected final function renderRating(): void {
        HtmlBuilder::openDiv('',$this->ratingId);
        $this->item->hasRating() 
            ? HtmlBuilder::showMessage('Rating: ' . $this->item->getRating() . ' (' . $this->item->getRatingCount() . ')')
            : HtmlBuilder::showMessage($this->noRatingMsg);
    
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
            if(!empty($this->message)) {
                HtmlBuilder::showMessage($this->message);
            } elseif ($this->canRate) {
                $ratingForm = new RatingForm($this->item->getId());
                $ratingForm->render();
            } elseif (!empty($this->ratingError)) {
                HtmlBuilder::showMessage($this->ratingError);
            }
        }
        HtmlBuilder::closeDiv();
    }

    private function makeCardForm(): void {
        !empty($_SESSION['logged_in']) ? $this->form = new CardForm($this->item->getId()) : $this->form = null;
    }

    public final function render(): void {
        $this->makeCardForm();
        $this->renderItemCard();
    }
    
    abstract protected function renderItemCard(): void;
    abstract protected function initialize(): void;
}