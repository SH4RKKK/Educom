<?php
function cart(array $response): void {
    if (!empty($response['message'])) {
        showMessage($response['message']);
    } elseif ($response['cart']) {
        showCart($response['cart'], 5.95);
    } else {
        showMessage('Winkelmandje is leeg');
    }
}
?>