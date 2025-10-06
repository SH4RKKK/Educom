<?php
$formFields = $response[$response['page']];

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    showTitle($response['message'], 'title');
} else {
    $msg = $response['message'] ?: (!empty($response['empty']) 
            ? 'Een of meerdere velden zijn leeg'
            : 'Welkom terug');

    showForm(
        'myForm',
        $msg,
        $formFields,
        $response['page'],
        $_POST ?? [],
        $response['empty'] ?? []
    );
}
?>