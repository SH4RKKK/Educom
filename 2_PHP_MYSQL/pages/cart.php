<?php
function cart(array $response): void {
    if (!empty($response['message'])) {
        showMessage($response['message']);
    } elseif ($response['item']) {
        showCart($response['item'], 5.95);
    } else {
        showMessage('Winkelmandje is leeg');
    }
}
?>