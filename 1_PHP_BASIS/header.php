<?php
openHTML();
makeHead($response['CSS'],'Saman');
openBody();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    showTitle('Hello ' . $_SESSION['username'],'title');
} else {
    showTitle('Hello stranger','title');
}

showListOfOptions('options',$response['menu']);
?>