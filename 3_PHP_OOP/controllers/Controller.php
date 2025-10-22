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
            case 'checkout': 
                $this->setItems();

                /*$result['cart'] = appendAmountToItem($_SESSION['orders'] ?? [], $result['items']);
                $result = array_merge($result, appendOrderToDatabase($request['db'], $_SESSION['user_id'], $result['cart']));
                */
                break;
            case 'order':
                $itemId = (int)($_POST['item_id']);
                $amount = (int)($_POST['amount']);

                if (!isset($_SESSION['orders'])) $_SESSION['orders'] = [];
                isset($_SESSION['orders'][$itemId]) ? $_SESSION['orders'][$itemId] += $amount : $_SESSION['orders'][$itemId] = $amount;
                isset($_POST['id']) ? $this->request['page'] = 'product' : $this->request['page'] = 'webshop';
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
                if (!empty($_SESSION['logged_in'])) {
                    session_unset();
                    session_destroy();
                }
                $this->request['page'] = 'login';
                break;
            case 'cart':
                $this->setItems();
                //$result['cart'] = appendAmountToItem($_SESSION['orders'] ?? [], $result['items']);
                break;
            default:
                break;
        }
    }

    private function showResponse() : void {
        if (!isset($this->body)) {
            $this->body = match($this->request['page']) {
                'about' => new About(),
                'cart' => new Cart($this->getCartItems()),
                'contact' => new Contact(),
                'login' => new Login(),
                'product' => new Product($this->getItemById($_GET['id'] ?? $_POST['id'] ?? 0)),
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
                $this->loginUser($_POST);
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
                $this->registerUser($_POST);
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

    private function loginUser(array $post): void {
        $user = $this->database->fetchUser($post['email']);
        if (empty($user)) {
            $this->error = 'E-mail niet gevonden';
            return;
        }

        if (!password_verify($post['wachtwoord'], $user['password'])) {
            $this->error = 'Incorrect wachtwoord';
            return;
        }

        $_SESSION['username'] = $user['name'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['logged_in'] = true;
    }

    private function registerUser(array $post): void {
        $user = $this->database->fetchUser($post['email']);
        if (!empty($user)) {
            $this->error = 'E-mail is al geregistreerd';
            return;
        }

        $hashedPassword = password_hash($post['wachtwoord'], PASSWORD_DEFAULT);
        $this->database->insertUser($post['naam'], $post['email'], $hashedPassword);
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
}