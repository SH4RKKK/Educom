<?php
$loginFields = [
    'E-mail',
    'Wachtwoord'
];

if (!empty($_SESSION['logged_in'])) {
    showTitle($response['message'], 'title');
} else {
    $msg = $response['message'] ?: (!empty($response['empty']) 
            ? 'Een of meerdere velden zijn leeg'
            : 'Welkom terug');

    showForm([
        'class'       => 'myForm',
        'formTitle'   => $msg,
        'fields'      => $loginFields,
        'action'      => $response['page'],
        'post'        => $_POST ?? [],
        'emptyFields' => $response['empty'] ?? []
    ]);
}
closeDiv();
?>