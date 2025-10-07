<?php
if (!empty($_SESSION['logged_in'])) {
    showTitle($response['message'], 'title');
} else {
    $msg = $response['message'] ?: (!empty($response['empty']) 
            ? 'Een of meerdere velden zijn leeg'
            : 'Welkom terug');

    showForm([
        'class'       => 'myForm',
        'formTitle'   => $msg,
        'fields'      => $response[$response['page']],
        'action'      => $response['page'],
        'post'        => $_POST ?? [],
        'emptyFields' => $result['empty'] ?? []
    ]);
}
?>