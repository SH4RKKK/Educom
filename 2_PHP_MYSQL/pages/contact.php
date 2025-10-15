<?php
function contact(array $response): void {
    if ($response['validated']) {
        showTitle('Bedankt voor jouw bericht, we reageren zo snel mogelijk!', 'title');
    } else {
        $msg = $response['message'] ?: (!empty($response['empty']) 
                ? 'Een of meerdere velden zijn leeg'
                : 'Vul gegevens in om in contact te komen!!!');
                
        showForm([
            'class'       => 'myForm',
            'formTitle'   => $msg,
            'fields'      => ['Naam','E-mail','Bericht'],
            'hidden'      => ['page' => $response['page']],
            'post'        => $_POST ?? [],
            'emptyFields' => $response['empty'] ?? [],
            'button' => ['desc'=> 'Vestuur'],
            'showLabel' => true,
            'addNewline' => true
        ]);
    }
}
?>