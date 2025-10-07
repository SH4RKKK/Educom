<?php
openHTML();
makeHead($response['CSS'],'Saman');
openBody();

if (!empty($_SESSION['logged_in'])) {
    showTitle('Hello ' . htmlspecialchars($_SESSION['username'],ENT_QUOTES),'title');
} else {
    showTitle('Hello stranger','title');
}

showListOfOptions('options',$response['menu']);
?>