<?php
if ($response['validated']) {
    showTitle($response['message'], 'title');
} else {
    $msg = $response['message'] ?: (!empty($response['empty']) 
            ? 'Een of meerdere velden zijn leeg'
            : 'Vul gegevens onderin aan om te registeren');

    showForm([
        'class'       => 'myForm',
        'formTitle'   => $msg,
        'fields'      => $response[$response['page']],
        'action'      => $response['page'],
        'post'        => $_POST ?? [],
        'emptyFields' => $response['empty'] ?? []
    ]);
}
closeDiv();
?>