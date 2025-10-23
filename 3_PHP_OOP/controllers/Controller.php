<?php
require_once '../base/HtmlPage.php';
require_once '../base/BodyContent.php';
require_once '../base/Item.php';

require_once '../pages/About.php';
require_once '../pages/Cart.php';
require_once '../pages/Contact.php';
require_once '../pages/Home.php';
require_once '../pages/Login.php';
require_once '../pages/Product.php';
require_once '../pages/Register.php';
require_once '../pages/Webshop.php';

require_once '../models/UserModel.php';
require_once '../models/ItemModel.php';
require_once '../models/ShopModel.php';

class Controller {
    private array $request;
    private $body;
    private Database $database;
    private int $productPerPage;

    private ?UserModel $userModel = null;
    private ?ItemModel $itemModel = null;
    private ?ShopModel $shopModel = null;

    private const PUBLIC_PAGES = ['home', 'about', 'contact', 'webshop', 'product'];
    private const GUEST_ONLY_PAGES = ['login', 'register'];
    private const AUTH_ONLY_PAGES = ['cart', 'checkout', 'logout', 'order'];

    public function __construct(Database $database,int $productPerPage = 2) {
        $this->database = $database;
        $this->productPerPage = $productPerPage;
    }

    private function getRequest(): void {
        $posted = ($_SERVER['REQUEST_METHOD']==='POST');
        $this->request = [
                'posted' => $posted,
                'page'     => $this->getRequestVar('page', $posted, 'home')    
            ];
    } 

    private function getRequestVar(string $key, bool $frompost, $default="", bool $asnumber=FALSE){
        $filter = $asnumber ? FILTER_SANITIZE_NUMBER_FLOAT : FILTER_SANITIZE_FULL_SPECIAL_CHARS;
        $result = filter_input(($frompost ? INPUT_POST : INPUT_GET), $key, $filter);
        return ($result===FALSE || $result===NULL) ? $default : $result;
    }

    private function validateRequest(): void {
        if (!$this->isPageAllowed($this->request['page'])) {
            $this->request['page'] = $this->isLoggedIn() ? 'home' : 'login';
            return;
        }
        
        $this->request['posted'] ? $this->handlePostRequest() : $this->handleGetRequest();
    }

    private function handlePostRequest(): void {
        $email = $this->getRequestVar('email', true);
        if(!empty($email)) $_POST['email'] = strtolower(trim($email));

        switch ($this->request['page']) {
            case 'order':
                $itemId = $this->getRequestVar('item_id', true, 0, true);
                $amount = $this->getRequestVar('amount', true, 0, true);
                $id = $this->getRequestVar('id', true, null, true);

                if (!isset($_SESSION['orders'])) $_SESSION['orders'] = [];
                isset($_SESSION['orders'][$itemId]) ? $_SESSION['orders'][$itemId] += $amount : $_SESSION['orders'][$itemId] = $amount;
                
                $this->request['page'] = !empty($id) ? 'product' : 'webshop';
                if ($this->request['page'] === 'product') $_GET['id'] = $id;
                break;
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
                session_unset();
                session_destroy();
                $this->request['page'] = 'login';
                break;
            case 'checkout': 
                $cartItems = $this->getShopModel()->getCartItems($this->getItemModel()->getItems());
                $this->getShopModel()->createOrder($_SESSION['user_id'],$cartItems);
                $this->request['page'] = 'cart';
                break;
            default:
                break;
        }
    }

    private function showResponse() : void {
        if (!isset($this->body)) {
            $this->body = match($this->request['page']) {
                'about' => new About(),
                'cart' => new Cart( $this->getShopModel()->getCartItems($this->getItemModel()->getItems()),$this->getShopModel()->getSuccesMsg() ?? $this->getShopModel()->getError() ?? ''),
                'contact' => new Contact(),
                'login' => new Login(),
                'product' => new Product($this->getItemModel()->getItemById($this->getRequestVar('id', $this->request['posted'], null, true)),$this->getItemModel()->getError ?? ''),
                'register' => new Register(),
                'webshop' => new Webshop($this->getItemModel()->getItems(),$this->productPerPage,$this->getItemModel()->getError ?? ''),
                default => new Home()
            };
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

    private function isLoggedIn(): bool {
        return !empty($_SESSION['logged_in']);
    }

    private function isPageAllowed(string $page): bool {
        if (in_array($page, self::PUBLIC_PAGES)) {
            return true;
        }
        
        if (in_array($page, self::GUEST_ONLY_PAGES)) {
            return !$this->isLoggedIn();
        }
        
        if (in_array($page, self::AUTH_ONLY_PAGES)) {
            return $this->isLoggedIn();
        }
        
        return false;
    }

    private function handleContact(): void {
        $contact = new Contact();
        if ($contact->validateForm()) {
            // Not implemented in this case
        }
    }
    
    private function handleLogin(): void {
        $this->body = new Login();
        if ($this->body->validateForm()) {
            try {
                $email = $this->getRequestVar('email', true);
                $password = $this->getRequestVar('wachtwoord', true);
                $this->getUserModel()->loginUser($email, $password);
                $error = $this->getUserModel()->getError();
                if(!empty($error)) $this->body->invalidateForm($error);
            } catch (Exception $e) {
                $this->body->invalidateForm('An error occurred: ' . $e->getMessage());
            }
        }
    }
    
    private function handleRegister(): void {
        $this->body = new Register();
        if ($this->body->validateForm()) {
            try {
                $name = $this->getRequestVar('naam', true);
                $email = $this->getRequestVar('email', true);
                $password = $this->getRequestVar('wachtwoord', true);
                $this->getUserModel()->registerUser($name, $email, $password);
                $error = $this->getUserModel()->getError();
                if (!empty($error)) $this->body->invalidateForm($error);
            } catch (Exception $e) {
                $this->body->invalidateForm('An error occurred: ' . $e->getMessage());
            }
        }
    }

    private function getUserModel(): UserModel {
        if ($this->userModel === null) {
            $this->userModel = new UserModel($this->database);
        }
        return $this->userModel;
    }

    private function getItemModel(): ItemModel {
        if ($this->itemModel === null) {
            $this->itemModel = new ItemModel($this->database);
        }
        return $this->itemModel;
    }
    private function getShopModel(): ShopModel {
        if ($this->shopModel === null) {
            $this->shopModel = new ShopModel($this->database);
        }
        return $this->shopModel;
    }
}