<?php
$contactFields = [
    'Naam',
    'E-mail',
    'Bericht'
];

if ($response['validated']) {
    showTitle('Bedankt voor jouw bericht, we reageren zo snel mogelijk!', 'title');
} else {
    $msg = $response['message'] ?: (!empty($response['empty']) 
            ? 'Een of meerdere velden zijn leeg'
            : 'Welkom terug');
            
    showForm([
        'class'       => 'myForm',
        'formTitle'   => $msg,
        'fields'      => $contactFields,
        'action'      => $response['page'],
        'post'        => $_POST ?? [],
        'emptyFields' => $response['empty'] ?? []
    ]);
}
closeDiv();
?>