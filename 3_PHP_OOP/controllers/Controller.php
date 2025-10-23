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

class Controller {
    private array $request;
    private $body;
    private Database $database;
    private string $error = '';
    private int $productPerPage;
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
                $cartItems = $this->getCartItems();
                $this->error = $this->database->appendOrder($_SESSION['user_id'],$cartItems);
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
                'cart' => new Cart($this->getCartItems(),$this->error ?? ''),
                'contact' => new Contact(),
                'login' => new Login(),
                'product' => new Product($this->getItemById($this->getRequestVar('id', $this->request['posted'], null, true))),
                'register' => new Register(),
                'webshop' => new Webshop($this->setItems(),$this->productPerPage,$this->error ?? ''),
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

    private function handleLogin(): void {
        $this->body = new Login();
        if ($this->body->validateForm()) {
            try {
                $email = $this->getRequestVar('email', true);
                $password = $this->getRequestVar('wachtwoord', true);
                $this->loginUser($email, $password);
                if(!empty($this->error)) $this->body->failForm($this->error);
            } catch (Exception $e) {
                $this->body->failForm('An error occurred: ' . $e->getMessage());
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
                $this->registerUser($name, $email, $password);
                if (!empty($this->error)) $this->body->failForm($this->error);
            } catch (Exception $e) {
                $this->body->failForm('An error occurred: ' . $e->getMessage());
            }
        }
    }

    private function handleContact(): void {
        $contact = new Contact();
        if ($contact->validateForm()) {
            // Not implemented in this case
        }
    }

    private function loginUser(string $email, string $password): void {
        $user = $this->database->fetchUser($email);
        if (empty($user)) {
            $this->error = 'E-mail niet gevonden';
            return;
        }

        if (!password_verify($password, $user['password'])) {
            $this->error = 'Incorrect wachtwoord';
            return;
        }

        $_SESSION['username'] = $user['name'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['logged_in'] = true;
    }

    private function registerUser(string $name, string $email, string $password): void {
        $user = $this->database->fetchUser($email);
        if (!empty($user)) {
            $this->error = 'E-mail is al geregistreerd';
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->database->insertUser($name, $email, $hashedPassword);
    }

    private function setItems(bool $includeDescription = false): array {
        try {
            $items = [];
            foreach ($this->database->fetchItems($includeDescription) as $itemData) {
                $items[] = new Item($itemData);
            }
            return $items;
        } catch (Exception $e) {
            $this->error = 'Failed to fetch items: ' .$e->getMessage();
            return [];
        }
    }

    private function getItemById(int $id): mixed {
        $items = $this->setItems(true);
        
        foreach ($items as $item) {
            if ($item->getId() === $id) {
                return $item;
            }
        }
        
        return null;
    }

    private function getCartItems(): array {
        $items = $this->setItems();
        $orders = $_SESSION['orders'] ?? [];
        $cartItems = [];
        
        foreach ($items as $item) {
            if (isset($orders[$item->getId()])) {
                $cartItems[] = [
                    'item' => $item,
                    'amount' => $orders[$item->getId()]
                ];
            }
        }
        
        return $cartItems;
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
}