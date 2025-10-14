<?php
if (empty($response['message'])) {
    $response['item'] ? showProductDetail($response['item']) : showMessage('Product bestaat niet');
} else {
    showMessage($response['message']);
}
closeDiv();
?>