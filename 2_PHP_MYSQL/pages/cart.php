<?php
$orders = $_SESSION['orders'] ?? [];
$products = $response['items'];

$cartItems = appendAmountToItem($orders, $products);

if (empty($response['message'])) {
    if($cartItems) {
        showCart($cartItems);
    } else {
        echo '<div class="cart-wrapper">';
        showMessage('Winkelmandje is leeg');
        echo '</div>';
    }
} else {
    showMessage($response['message']);
}
closeDiv();
?>