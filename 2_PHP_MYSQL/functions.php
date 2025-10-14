<?php
//Open HTML
function openHTML(): void {
    echo '<html>';
}

//Makes the head part of the HTML document
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
function openDiv($class = ''): void { //Use this type of argument
    echo '<div' . ($class ? ' class="' . $class . '"' : '') . '>';
}

//Show title
function showTitle(string $title,string $class): void {
    echo '<h1 class="'. $class . '">' . $title . '</h1>';
}

//Show list of options
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
function showMessage(string $message): void {
    echo '<p>' . $message . '</p>';
}

//Validate input of form by checking if they are empty
//Returns all empty fields
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
//    showForm([
//        'class'       => ,
//        'formTitle'   => ,
//        'fields'      => ,
//        'action'      => ,
//        'post'        =>  ?? [],
//        'emptyFields' =>  ?? []
//    ]);
function showForm(array $config): void {
    echo '<form class="' . $config['class'] . '" method="post" action="index.php">';
    echo '<legend><h2>' . $config['formTitle'] . ':</h2></legend>';
    echo '<input type="hidden" name="page" value="' . $config['action'] . '">';

    foreach ($config['fields'] as $field) {
        $cleanedField = slugify($field);

        $info = htmlspecialchars(isset($config['post'][$cleanedField]) ? $config['post'][$cleanedField] : '',  ENT_QUOTES);
        $isEmpty = in_array($cleanedField, $config['emptyFields']);

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

//Close div
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

//Check whether given email exists in database
function checkEmail(mysqli $conn, string $email): array {

    $sql  = "SELECT id, name, email, password 
             FROM users 
             WHERE email = ? 
             LIMIT 1";

    try {
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        mysqli_stmt_close($stmt);
        
        return $row ? [$row, true, ''] : [null, false, ''];
    } catch (Throwable $e) {
        if (isset($stmt)) mysqli_stmt_close($stmt);
        return [null, false, 'Error checking email: ' . $e];
    }
}

//Handle the input of the login
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
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['logged_in'] = true;
    return 'Login succesvol';
}

//Handles the input from register
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
function appendUserToDatabase(mysqli $conn,array $data): array {
    $result = [
        'validated' => false, 
        'message' =>  ''
    ];

    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

    try {
        $stmt = mysqli_prepare($conn, $sql);
        $hashedPassword = password_hash($data['wachtwoord'], PASSWORD_DEFAULT);
        
        mysqli_stmt_bind_param($stmt, "sss", $data['naam'], $data['email'], $hashedPassword);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        $result['validated'] = true;
    } catch (Throwable $e) {
        if (isset($stmt)) mysqli_stmt_close($stmt);
        $result['message'] = 'Error adding user: ' . $e;
    }
    
    return $result;
}

function fetchItems (array &$result): void {

    $sql = "SELECT id, name, price, image_path FROM items ORDER BY id ASC";

    $products = [];
    try {
        $resultQuery = mysqli_query($result['db'], $sql);

        if ($resultQuery && mysqli_num_rows($resultQuery) > 0) {
            while ($row = mysqli_fetch_assoc($resultQuery)) {
                $products[] = $row;
            }
            $result['items'] = $products;
        } else {
            $result['message'] = 'Database does not have items';
        }

    } catch (Throwable $e) {
         $result['message'] = 'Get items query failed on database: ' . $e;
    }
}

function fetchItemDetails (array &$result, int $itemID): void {
    $sql  = "SELECT * 
             FROM items 
             WHERE id = ?";

    try {
        $stmt = mysqli_prepare($result['db'], $sql);
        mysqli_stmt_bind_param($stmt, "i", $itemID);
        mysqli_stmt_execute($stmt);
        
        $resultQuery = mysqli_stmt_get_result($stmt);
        $result['item'] = mysqli_fetch_assoc($resultQuery);
        
        mysqli_stmt_close($stmt);
    } catch (Throwable $e) {
        if (isset($stmt)) mysqli_stmt_close($stmt);
        $result['message'] = 'Error fetching item details: ' . $e;
    }
}

//Fix in future + CSS
function showProducts(array $productsToShow): void {
    openDiv('page');
    foreach($productsToShow as $p) {
        openDiv('card');
        
        echo '<a href="index.php?page=product&id=' . (int)$p['id'] . '" class="card-link">';
        echo '<img src="images/' . $p['image_path'] . '" alt="'  . $p['name'] . '">';
        echo '<div class="card-content">';
        echo $p['name'] . ' - €' . number_format($p['price'], 2, ',', '');
        echo '</div>';
        echo '</a>';
        
        openDiv('actions');
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

//Fix in future + CSS
function showPagination(int $currentPage, int $totalPages): void {
    echo '<form method="post" style="text-align:center; margin-top:20px;" action="index.php">';
    echo '<input type="hidden" name="page" value="webshop">';
    
    for ($i = 1; $i <= $totalPages; $i++) {
        $disabled = $i === $currentPage ? 'disabled style="font-weight:bold;"' : '';
        echo '<button type="submit" name="webshoppage" value="' . $i . '" ' . $disabled . '>';
        echo $i;
        echo '</button>';
    }
    echo '</form>';
}

function showProductDetail(array $product): void {
    openDiv('product-detail');
    
    openDiv('product-image-section');
    echo '<img src="images/' . $product['image_path'] . '" alt="' . $product['name'] . '">';
    closeDiv();
    
    openDiv('product-info-section');
    echo '<h2>' . $product['name'] . '</h2>';
    echo '<p class="product-price">€' . number_format($product['price'], 2, ',', '') . '</p>';
    
    if (!empty($product['description'])) {
        echo '<p class="product-description">' . htmlspecialchars($product['description']) . '</p>';
    }
    
    if (!empty($_SESSION['logged_in'])) {
        echo '<form method="post" action="index.php" class="product-order-form">';
        echo '<input type="hidden" name="page" value="order">';
        echo '<input type="hidden" name="item_id" value="' . (int)$product['id'] . '">';
        echo '<input type="number" name="amount" min="1" value="1">';
        echo '<button type="submit">Order Now</button>';
        echo '</form>';
    } else {
        echo '<a href="index.php?page=login" class="login-prompt">Login to order!</a>';
    }
    
    closeDiv();
    closeDiv();
}

function appendAmountToItem (array $session, array $items): array {
    return array_filter(array_map(function($product) use ($session) {
    $id = $product['id'];
    if (isset($session[$id])) {
        $product['amount'] = $session[$id];
        return $product;
    }
    return null;
}, $items));
}

//Fix in future + CSS
function showCart(array $cartItems): void {
    
    $total = 0;
    
    echo '<div class="cart-wrapper">';
    echo '<h1 class="cart-title">Shopping Cart</h1>';
    echo '<div class="cart-container">';
    echo '<div class="cart-items">';
    echo '<table class="cart-table">';
    echo '<thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr></thead>';
    echo '<tbody>';
    
    foreach ($cartItems as $item) {
        $subtotal = $item['price'] * $item['amount'];
        $total += $subtotal;
        
        echo '<tr>';
        echo '<td class="cart-product">';
        if (!empty($item['image_path'])) {
            echo '<img src="images/' . htmlspecialchars($item['image_path']) . '" alt="' . htmlspecialchars($item['name']) . '">';
        }
        echo '<span>' . htmlspecialchars($item['name']) . '</span>';
        echo '</td>';
        echo '<td>€' . number_format($item['price'], 2, ',', '.') . '</td>';
        echo '<td>' . (int)$item['amount'] . '</td>';
        echo '<td>€' . number_format($subtotal, 2, ',', '.') . '</td>';
        echo '</tr>';
    }
    
    $shipping = 5.95;
    echo '</tbody></table></div>';
    
    echo '<div class="cart-summary">';
    echo '<h3>Cart totals</h3>';
    echo '<div class="summary-row"><span>Subtotal</span><span>€' . number_format($total, 2, ',', '.') . '</span></div>';
    echo '<div class="summary-row"><span>Shipping</span><span>€' . number_format($shipping, 2, ',', '.') . '</span></div>';
    $total += $shipping;
    echo '<div class="summary-total"><span>Total</span><span>€' . number_format($total, 2, ',', '.') . '</span></div>';
    echo '<form method="post" action="index.php">';
    echo '<input type="hidden" name="page" value="cart">';
    echo '<button type="submit" class="checkout-btn">Proceed to checkout</button>';
    echo '</form>';
    echo '</div></div></div>';
}

function appendOrderToDatabase(mysqli $conn, int $userId, array $cartItems): array {
    $result = [
        'validated' => false, 
        'message' => ''
    ];

    try {
        mysqli_begin_transaction($conn);
        
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['amount'];
        }
        
        //orders table
        $sqlOrder = "INSERT INTO orders (user_id, status_id, total) VALUES (?, 1, ?)";
        $stmt = mysqli_prepare($conn, $sqlOrder);
        mysqli_stmt_bind_param($stmt, "id", $userId, $total);
        mysqli_stmt_execute($stmt);
        
        $orderId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        
        //order_items table
        $sqlItem = "INSERT INTO order_items (order_id, item_id, amount, unit_price) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sqlItem);
        
        foreach ($cartItems as $item) {
            mysqli_stmt_bind_param($stmt, "iiid", $orderId, $item['id'], $item['amount'], $item['price']);
            mysqli_stmt_execute($stmt);
        }
        
        mysqli_stmt_close($stmt);
        
        mysqli_commit($conn);
        
        $result['validated'] = true;
        $result['message'] = 'Order placed successfully! Order #' . $orderId;
        unset($_SESSION['orders']);
        
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        if (isset($stmt)) mysqli_stmt_close($stmt);
        $result['message'] = 'Error adding order: ' . $e;
    }
    
    return $result;
}
?>