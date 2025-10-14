<?php
$registerFields = [
    'Naam',
    'E-mail', 
    'Wachtwoord',
    'Herhaal wachtwoord'
];

if ($response['validated']) {
    showTitle($response['message'], 'title');
} else {
    $msg = $response['message'] ?: (!empty($response['empty']) 
            ? 'Een of meerdere velden zijn leeg'
            : 'Vul gegevens onderin aan om te registeren');

    showForm([
        'class'       => 'myForm',
        'formTitle'   => $msg,
        'fields'      => $registerFields,
        'action'      => $response['page'],
        'post'        => $_POST ?? [],
        'emptyFields' => $response['empty'] ?? []
    ]);
}
closeDiv();
?>