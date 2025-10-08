<?php
//Open HTML
function openHTML(): void {
    echo '<html>';
}

//Makes the head part of the HTML document
//$path is a string to the path of the CSS-file
//$title is a string that is the title of the page
function makeHead(string $path,string $title): void {

    if (!file_exists($path)) {
        echo "CSS File niet gevonden";
        exit;
    }

    echo '<head><link rel="stylesheet" href="'. $path . '"><title>'. $title . '</title></head>';
}

//Open body
function openBody(): void {
    echo '<body><div class="content">';
}

//Open div
function openDiv($class = ''): void {
    echo '<div' . ($class ? ' class="' . $class . '"' : '') . '>';
}

//Show title
//$title is the title that you want to show on the page
//$class is the style-class you want to use for the title
function showTitle(string $title,string $class): void {
    echo '<h1 class="'. $class . '">' . $title . '</h1>';
}

//Show list of options
//$class is the style-class you want to use for the list
//$options is an array of strings that are the options
function showListOfOptions(string $class,array $options): void {
    echo '<ul class="'. $class . '">';

    foreach ($options as $option) {

        $builder = '<li><a href="index.php?page='. strtolower($option) .'">' . $option;

        if (!empty($_SESSION['logged_in']) && $option === 'LOGOUT') $builder .= ' ' . htmlspecialchars($_SESSION['username'], ENT_QUOTES);
        echo $builder.= '</a></li>';
    }
    echo '</ul>';
}

//Show message
//$message is the message you want to present in the body
function showMessage(string $message): void {
    echo '<p>' . $message . '</p>';
}

//Validate input of form by checking if they are empty
//Option to show the input if $showInput is true
//return true if all fields are filled else return false
function inputValidationForm(array $post) : array {
    $empty = [];

    foreach ($post as $key => $value) {
        if (trim($value) === '') {
            $empty[] = $key;
        }
    }

    return $empty;
}

//Build the form with or without POST data
//$class is the class-style used for the form
//$formTitle is the title of the 
//$fiels is an array of strings that are the forms
//function showForm(string $class, string $formTitle, array $fields, string $action, array $post = [], array $emptyFields = []): void {
function showForm(array $config): void {
    $class       = $config['class'];
    $formTitle   = $config['formTitle'];
    $fields      = $config['fields'];
    $action      = $config['action'];
    $post        = $config['post'];
    $emptyFields = $config['emptyFields'];

    echo '<form class="' . $class . '" method="post" action="index.php">';
    echo '<legend><h2>' . $formTitle . ':</h2></legend>';
    echo '<input type="hidden" name="page" value="' . $action . '">';

    foreach ($fields as $field) {
        $cleanedField = slugify($field);

        $info = htmlspecialchars(isset($post[$cleanedField]) ? $post[$cleanedField] : '',  ENT_QUOTES);
        $isEmpty = in_array($cleanedField, $emptyFields);

        echo '<label for="' . $cleanedField . '">' . $field . ':</label>';

        $inputClass = $isEmpty ? 'error' : '';

        if (str_contains($cleanedField,'wachtwoord')) {
            echo '<input type="password" class="' . $inputClass . '" id="' . $cleanedField . '" name="' . $cleanedField . '" value="' . $info . '" autocomplete="new-password">';
        } else {
            echo '<input type="text" class="' . $inputClass . '" id="' . $cleanedField . '" name="' . $cleanedField . '" value="' . $info . '">';
        }

        if ($isEmpty) {
        echo '<span class="error">Veld is leeg!</span>';
        }

        echo '<br>';
    }

    echo '<input type="submit" value="Verstuur">';
    echo '</form>';
}

function closeDiv(): void {
    echo '</div>';
}

//Close body
function closeBody(): void {
    echo '</div></body>';
}

//Make the footer of the page
function makeFooter(): void {
    echo '<footer>&copy ' . date("Y") . ' Saman Ahmad</footer>';
}

//Close HTML
function closeHTML(): void {
    echo '</html>';
}

//Function to check whether the given request to the server is a POST or GET
//It also returns the page that the request was originated from
function getRequest() : array
{
    $posted = ($_SERVER['REQUEST_METHOD']==='POST');
    return  
        [
            'posted' => $posted,
            'page'     => getRequestVar('page', $posted, 'home')    
        ];
} 

//Function that gets the variable from a request
function getRequestVar(string $key, bool $frompost, $default="", bool $asnumber=FALSE)
{
    $filter = $asnumber ? FILTER_SANITIZE_NUMBER_FLOAT : FILTER_SANITIZE_STRING;
    $result = filter_input(($frompost ? INPUT_POST : INPUT_GET), $key, $filter);
    return ($result===FALSE) ? $default : $result;
}   

//Helper function te remove illegal characters in a string when using it as name and id
function slugify(string $text): string {
    $text = strtolower(trim($text));
    $text = str_replace([' ', '-', '_'], '', $text);
    $text = preg_replace('/[^a-z0-9]/', '', $text);
    return $text;
}

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

//Function to include files that build the page
function showResponse(array $response) : void {
    include 'header.php';
    include 'pages/' . $response['page'] . '.php';
    include 'footer.php';
} 

//Connect to database
function connectDataBase(array $dataBase) {
    $conn = mysqli_connect(
        $dataBase['servername'],
        $dataBase['username'],
        $dataBase['password'],
        $dataBase['dbName']
    );

    return $conn;
}

//Check whether given email exists in database
function checkEmail(mysqli $conn, string $email): array {

    $sql  = "SELECT id, name, email, password 
             FROM users 
             WHERE email = ? 
             LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return [null, false, 'Error in statement: ' . mysqli_error($conn)];
    }

    if (!mysqli_stmt_bind_param($stmt, "s", $email)) {
        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        return [null, false, "Error in binding: " . $error];
    }

    if (!mysqli_stmt_execute($stmt)) {
        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        return [null, false, "Error in execution: " . $error];
    }

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if ($row) {
        return [$row, true, ''];
    } else {
        return [null, false, ''];
    }
}

//Handle the input of the login
//$conn is the connection to the user database
//returns a message regarding how the login was handled
function handleLogin(mysqli $conn,array $post) : string {
    $foundEmail = checkEmail($conn,$post['email']);

    if(!$foundEmail[1]) {
        return $foundEmail[2] ?: 'E-mail niet gevonden';
    }

    $row = $foundEmail[0];
    if (empty($row['name']) || empty($row['password'])) {
        return 'Fout in database, lege waardes';
    }

    if (!password_verify($post['wachtwoord'], $row['password'])) {
        return 'Incorrect wachtwoord';
    }

    $_SESSION['username'] = $row['name'];
    $_SESSION['logged_in'] = true;
    return 'Login succesvol';
}

//Handles the input from register
//$conn is the connection to the user database
//Returns whether register was successful as a boolean and also a message that contains an error message or success message
function handleRegister(mysqli $conn,array $post): array {  
    $result = [
        'validated' => false, 
        'message' =>  ''
    ];

    $foundEmail = checkEmail($conn,$post['email']);

    if(!empty($foundEmail[2])) {
        $result['message'] = $foundEmail[2];
        return $result;
    }

    if ($foundEmail[1]) {
        $result['message'] = 'E-mail is al geregistreerd';
        return $result;
    }

    if($post['wachtwoord'] === $post['herhaalwachtwoord']) {
        $appendResult = appendUserToDatabase($conn,$post);

        if (!$appendResult['validated']) {
            $result['message'] = $appendResult['message'];
            return $result;
        }

        $result['validated'] = true;
        $result['message'] = 'Succesvol geregisteerd!!!';

        return $result;
    }

    $result['message'] = 'Wachtwoorden komen niet overeen';
    return $result;
}

//Append newly registered user to the file
//$path is the path to the users file
//$submitted is the POST data that was submitted
function appendUserToDatabase(mysqli $conn,array $data): array {
    $result = [
        'validated' => false, 
        'message' =>  ''
    ];

    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        $result['message'] = 'Error in statement: ' . mysqli_error($conn);
        return $result;
    }

    $hashedPassword = password_hash($data['wachtwoord'], PASSWORD_DEFAULT);

    if (!mysqli_stmt_bind_param($stmt, "sss", $data['naam'],$data['email'],$hashedPassword)) {
        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);

        $result['message'] = "Error in binding: " . $error;
        return $result;
    }

    $result['validated'] = mysqli_stmt_execute($stmt);

    if (!$result['validated']) {
        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);

        $result['message'] = "Error in execution: " . $error;
        return $result;
    }

    mysqli_stmt_close($stmt);
    return $result;
}

function fetchItems(mysqli $conn): array {
    $sql = "SELECT id, name, description, price, image_path FROM items ORDER BY id ASC";
    $result = mysqli_query($conn, $sql);

    $products = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }

    return $products;
}

function showProducts(int $productPerPage, int $currentPage, array $products): void {
    $totalPages = ceil(count($products) / $productPerPage);
    $displayPage = max(1, min($currentPage, $totalPages));
    $startIndex  = ($displayPage - 1) * $productPerPage;

    $productsToShow = array_slice($products, $startIndex, $productPerPage);

    //Dit moet nog gefixt worden + CSS
    openDiv('page');
    foreach($productsToShow as $p) {
        openDiv('card');
        echo '<img src="images/' . $p['image_path'] . '" alt="'  . $p['name'] . '">';
        openDiv('actions');
        echo $p['name'] . '- €' . number_format($p['price'], 2, ',', '') ;

        if(!empty($_SESSION['logged_in'])) {
            echo '<form method="post" action="index.php">';
            echo '<input type="hidden" name="page" value="order">';
            echo '<input type="hidden" name="item_id" value="' . (int)$p['id'] . '">';
            echo '<input type="number" name="amount" min="1" value="1">';
            echo '<button type="submit">Order Now</button>';
            echo '</form>';
        } else {
            echo '<a href="index.php?page=login">Login to order!</a>';
        }

        closeDiv();
        closeDiv();
    }
    
    closeDiv();
}

function handleWebshopReq (array &$result): void {
    $conn = connectDataBase($result['itemDatabase']);

    if (!$conn) {
        $result['message'] = "Connection failed: " . mysqli_connect_error();
        return;
    }

    $products = fetchItems($conn);
    if(empty($products)) {
        $result['message'] = 'Failed to fetch products';
        mysqli_close($conn);
        return;
    }

    $result['items'] = $products;
    mysqli_close($conn);
}
?>