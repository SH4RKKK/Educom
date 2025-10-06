<?php
$formFields = $response[$response['page']];

if ($response['validated']) {
    showTitle('Bedankt voor jouw bericht, we reageren zo snel mogelijk!', 'title');
} else {
    $msg = (!empty($response['empty']) ? 'Een of meerdere velden zijn leeg' : 'Vul gegevens in om in contact te komen');

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