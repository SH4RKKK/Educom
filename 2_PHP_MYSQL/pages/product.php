<?php
function product(array $response): void{
    if (empty($response['message'])) {
        $response['item'] ? showProductDetail($response['item']) : showMessage('Product bestaat niet');
    } else {
        showMessage($response['message']);
    }
}
?>