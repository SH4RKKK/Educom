<?php
function createHeader(array $response): void {
    openHTML();
    makeHead($response['CSS'],'Saman');
    openBody();
    openDiv('content');
    if (!empty($_SESSION['logged_in'])) {
        showTitle('Hello ' . htmlspecialchars($_SESSION['username'],ENT_QUOTES),'title');
    } else {
        showTitle('Hello stranger','title');
    }

    showListOfOptions('options',$response['menu']);
}

//Function to include files that build the page
function showResponse(array $response) : void {
    createHeader($response);
    include 'pages/' . $response['page'] . '.php';
    footer();
} 

function footer() : void {
    makeFooter();
    closeBody();
    closeHTML();
}
?>