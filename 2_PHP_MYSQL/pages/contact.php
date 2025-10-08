<?php
if ($response['validated']) {
    showTitle('Bedankt voor jouw bericht, we reageren zo snel mogelijk!', 'title');
} else {
    $msg = (!empty($response['empty']) ? 'Een of meerdere velden zijn leeg' : 'Vul gegevens in om in contact te komen');

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