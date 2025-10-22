<?php
require_once '../base/HtmlPage.php';
require_once '../base/BodyContent.php';
require_once '../pages/About.php';
require_once '../pages/Cart.php';
require_once '../pages/Contact.php';
require_once '../pages/Home.php';
require_once '../pages/Login.php';
require_once '../pages/Product.php';
require_once '../pages/Register.php';
require_once '../pages/Webshop.php';

class Controller {
    private array $request,$items;
    private BodyContent $body;

    private function getRequest(): void {
        $posted = ($_SERVER['REQUEST_METHOD']==='POST');
        $this->request = [
                'posted' => $posted,
                'page'     => $this->getRequestVar('page', $posted, 'home')    
            ];
    } 

    private function getRequestVar(string $key, bool $frompost, $default="", bool $asnumber=FALSE){
        $filter = $asnumber ? FILTER_SANITIZE_NUMBER_FLOAT : FILTER_SANITIZE_STRING;
        $result = filter_input(($frompost ? INPUT_POST : INPUT_GET), $key, $filter);
        return ($result===FALSE) ? $default : $result;
    } 

    private function validateRequest(): void {
        $this->request['posted'] ? $this->handlePostRequest() : $this->handleGetRequest();
    }

    private function handlePostRequest(): void {
        if(isset($_POST['email'])) $_POST['email'] = strtolower(trim($_POST['email']));
        switch ($this->request['page']) {
            case 'checkout': /*
                fetchItems($result);
                $result['cart'] = appendAmountToItem($_SESSION['orders'] ?? [], $result['items']);
                $result = array_merge($result, appendOrderToDatabase($request['db'], $_SESSION['user_id'], $result['cart']));
                */
                break;
            case 'order': /*
                $itemId = (int)($_POST['item_id']);
                $amount = (int)($_POST['amount']);

                if (!isset($_SESSION['orders'])) {
                    $_SESSION['orders'] = [];
                }

                if (isset($_SESSION['orders'][$itemId])) {
                    $_SESSION['orders'][$itemId] += $amount;
                } else {
                    $_SESSION['orders'][$itemId] = $amount;
                }

                if (isset($_POST['id'])) {
                    $result['page'] = 'product';
                    fetchItemDetails($result, (int)$_POST['id']);
                } else {
                    $result['page'] = 'webshop';
                    fetchItems($result);
                }
                break;*/
            case 'login':
                $this->handleLogin();
                break;
            case 'register':
                $this->handleRegister();
                break;   
            case 'contact':
                $this->handleContact();
                break;
            default:
                break;
        }
    }

    private function handleGetRequest(): void {
        switch ($this->request['page']) {
            case 'logout':
                if (!empty($_SESSION['logged_in'])) {
                    session_unset();
                    session_destroy();
                }
                $this->request['page'] = 'login';
                break;
            case 'webshop':
                //fetchItems($result);
                break;
            case 'cart':
                //fetchItems($result);
                //$result['cart'] = appendAmountToItem($_SESSION['orders'] ?? [], $result['items']);
                break;
            case 'product':/*
                try {
                    fetchItemDetails($result,$_GET['id']);
                } catch (Throwable $e) {
                    $result['message'] = 'Invalid product argument';
                }*/
            default:
                break;
        }
    }

    private function showResponse() : void {
        switch($this->request['page']) {
            case 'about':
                $this->body = new About();
                break;
            case 'cart':
                //$this->body = new Cart();
                break;
            case 'contact':
                $this->body = new Contact();
                break;
            case 'login':
                $this->body = new Login();
                break;
            case 'product':
                //$this->body = new Product();
                break;   
            case 'register':
                $this->body = new Register();
                break;
            case 'webshop':
                //$this->body = new Webshop();
                break;
            case 'home':
            default:
                $this->body = new Home();
                break;
        }

        $page = new HtmlPage(
            "Saman's Whey",
            'Saman Ahmad',
            '../css/style.css',
            'content',
            $this->body
        );

        $page->show();
    }

    public final function showPage(): void {
        $this->getRequest();
        $this->validateRequest();
        $this->showResponse();
    }

    private function handleLogin(): void {
        $login = new Login();
        if ($login->ValidateForm()) {
            //handle login
        }
    }

    private function handleRegister(): void {
        $register = new Register();
        if ($register->ValidateForm()) {
            //handle register 
        }
    }

    private function handleContact(): void {
        $contact = new Contact();
        if ($contact->ValidateForm()) {
            // Not implemented in this case
        }
    }
}