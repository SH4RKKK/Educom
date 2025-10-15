<?php
function login(array $response): void {
    if (!empty($_SESSION['logged_in'])) {
        showTitle($response['message'], 'title');
    } else {
        $msg = $response['message'] ?: (!empty($response['empty']) 
                ? 'Een of meerdere velden zijn leeg'
                : 'Welkom terug');
        
        showForm([
            'class'       => 'myForm',
            'formTitle'   => $msg,
            'fields'      => ['E-mail','Wachtwoord'],
            'hidden'      => [ 'page' => $response['page']],
            'post'        => $_POST ?? [],
            'emptyFields' => $response['empty'] ?? [],
            'button' => ['desc'=> 'Login'],
            'showLabel' => true,
            'addNewline' => true
        ]);
    }
}
?>