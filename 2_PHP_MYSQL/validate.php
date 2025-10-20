<?php
//Function to validate the request received by the server
function validateRequest(array $request) : array {
    //Initialize keys to process request
    $result = [
        ...$request,
        'message' => '',
        'validated' => false,
        'empty' => [],
        'items' => [],
        'item' => [],
        'cart' => []
    ];

    //POST
    if ($request['posted']) {
        $result['empty'] = inputValidationForm($_POST);
        if(isset($_POST['email'])) $_POST['email'] = strtolower(trim($_POST['email']));
        
        if (!empty($result['empty'])) {
            return $result;
        }

        if (!$result['db']) {
            $result['message'] = 'DATABASE IS DOWN';
            return $result;
        }

        switch ($request['page']) {
            case 'cart':
                fetchItems($result);
                $result['cart'] = appendAmountToItem($_SESSION['orders'] ?? [], $result['items']);
                $result = array_merge($result, appendOrderToDatabase($request['db'], $_SESSION['user_id'], $result['cart']));
                break;
            case 'webshop':
                $_SESSION['webshoppage'] = (int)$_POST['webshoppage'];
                fetchItems($result);
                break;
            case 'order':
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
                break;
            case 'login':
                $result['message'] = handleLogin($request['db'], $_POST);
                break;
            case 'register':
                $result = array_merge($result, handleRegister($request['db'], $_POST));
                break;
            default:
                $result['validated'] = true;
                break;
        }
    //GET where the request needs to be validated
    } else {
        switch ($request['page']) {
            case 'logout':
                if (!empty($_SESSION['logged_in'])) {
                    session_unset();
                    session_destroy();
                    unset($result['menu']['LOGOUT']);
                }
                $result['page'] = 'login';
                break;
            case 'webshop':
                fetchItems($result);
                break;
            case 'cart':
                fetchItems($result);
                $result['cart'] = appendAmountToItem($_SESSION['orders'] ?? [], $result['items']);
                break;
            case 'product':
                try {
                    fetchItemDetails($result,$_GET['id']);
                } catch (Throwable $e) {
                    $result['message'] = 'Invalid product argument';
                }
            default:
                break;
        }
    }

    //Remove LOGIN and REGISTER if user logged in
    if (!empty($_SESSION['logged_in'])) {
        $result['menu'] = array_diff($request['menu'], ['LOGIN', 'REGISTER']);
        $result['menu'] = array_merge($result['menu'], ['CART', 'LOGOUT']);
    }
    
    //Add new pages
    $allowedPages = array_merge($result['menu'], ['product']);
    //Check if page is allowed otherwise return to home
    if (!in_array($result['page'],array_map('strtolower', $allowedPages))) $result['page'] = 'home';

    return $result;
} 
?>