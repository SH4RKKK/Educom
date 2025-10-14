<?php
//Function to validate the request received by the server
function validateRequest(array $request) : array {
    //Initialize keys to process request
    $result = [
        ...$request,
        'message' => '',
        'validated' => false,
        'empty' => [],
    ];

    //POST
    if ($request['posted']) {
        $result['empty'] = inputValidationForm($_POST);
        
        if (!empty($result['empty'])) {
            return $result;
        }

        switch ($request['page']) {
            case 'webshop':
                $_SESSION['webshoppage'] = (int)$_POST['webshoppage'];
                handleWebshopReq($result);
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
                $result['page'] = 'webshop';
                handleWebshopReq($result);
                break;
            case 'login':
            case 'register':
                $_POST['email'] = strtolower(trim($_POST['email']));

                $conn = connectDataBase($request['userDatabase']);
                
                if (!$conn) {
                    $result['message'] = "Connection failed: " . mysqli_connect_error();
                    return $result;
                }

                if ($request['page'] === 'login') {
                    $result['message'] = handleLogin($conn, $_POST);
                } else {
                    $result = array_merge($result, handleRegister($conn, $_POST));
                }

                mysqli_close($conn);
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
                    handleWebshopReq($result);
                break;
            default:
                break;
        }
    }

    //Remove LOGIN and REGISTER if user logged in
    if (!empty($_SESSION['logged_in'])) {
        $result['menu'] = array_diff($request['menu'], ['LOGIN', 'REGISTER']);
        $result['menu'] = array_merge($result['menu'], ['CART', 'LOGOUT']);
    }
    
    //Check if page is allowed otherwise return to home
    if (!in_array($result['page'],array_map('strtolower', $result['menu']))) $result['page'] = 'home';

    return $result;
} 
?>