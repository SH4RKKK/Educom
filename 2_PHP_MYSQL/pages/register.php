<?php
$formFields = $response[$response['page']];

if ($response['validated']) {
    showTitle($response['message'], 'title');
} else {
    $msg = $response['message'] ?: (!empty($response['empty']) 
            ? 'Een of meerdere velden zijn leeg'
            : 'Vul gegevens onderin aan om te registeren');

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