<?php
function register(array $response): void {
    if (!empty($_SESSION['logged_in'])) {
        showTitle($response['message'], 'title');
    } else {
        $msg = $response['message'] ?: (!empty($response['empty']) 
            ? 'Een of meerdere velden zijn leeg'
            : 'Vul gegevens onderin aan om te registeren');
        
        showForm([
            'class'       => 'myForm',
            'formTitle'   => $msg,
            'fields'      => ['Naam','E-mail', 'Wachtwoord','Herhaal wachtwoord'],
            'hidden'      => ['page' => $response['page']],
            'post'        => $_POST ?? [],
            'emptyFields' => $response['empty'] ?? [],
            'button' => ['desc'=> 'Registreer'],
            'showLabel' => true,
            'addNewline' => true
        ]);
    }
}
?>