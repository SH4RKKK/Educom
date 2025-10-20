<?php
//makes the complete begin of the page that is consistent every page
function createHeader(array $response): void {
    openHTML();
    makeHead($response['CSS'],"Saman's Whey");
    openBody();
    openDiv('content');

    if (!empty($_SESSION['logged_in'])) {
        showTitle('Hello ' . htmlspecialchars($_SESSION['username'],ENT_QUOTES),'title');
    } else {
        showTitle('Hello stranger','title');
    }

    showListOfOptions('options',$response['menu']);
}

//function to include files that build the page
function showResponse(array $response) : void {
    createHeader($response);
    //include 'pages/' . $response['page'] . '.php';
    
    switch($response['page']) {
        case 'about':
            about();
            break;
        case 'cart':
            cart($response);
            break;
        case 'contact':
            contact($response);
            break;
        case 'login':
            login($response);
            break;
        case 'product':
            product($response);
            break;   
        case 'register':
            register($response);
            break;
        case 'webshop':
            webshop($response,2,$_SESSION['webshoppage'] ?? 1);
            break;
        case 'home':
        default:
            home();
            break;
    }

    createFooter();
} 

//makes the complete bottom of the page that is consistent on every page
function createFooter() : void {
    closeDiv();
    makeFooter();
    closeBody();
    closeHTML();
}
?>