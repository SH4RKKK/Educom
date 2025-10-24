<?php
require_once 'Item.php';

class ItemRating {
    private ?float $itemRating = null;
    private string $error = '';

    public function __construct(?float $itemRating = null,string $error) {
        $this->itemRating = $itemRating;
        $this->error = $error;
    }
    
    public final function render(): void {
        if ($this->itemRating !== null) {
            //display rating
        }  else {
            // Prouct heeft geen rating
        }

        if ($_SESSION['logged_in']) {
            //Retrieve results of can rate 
                //This consists out of ordered before and has not rated before
                    //Display buttons 1 to 5 that gets handled in controller
        } 
    }

}