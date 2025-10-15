<?php

//--------------HTML HELPER functions
function openHTML(): void {
    echo '<html>';
}

function closeHTML(): void {
    echo '</html>';
}

function openBody(): void {
    echo '<body>';
}

function closeBody(): void {
    echo '</body>';
}

function openDiv(string $class = ''): void {
    echo '<div' . ($class ? ' class="' . $class . '"' : '') . '>';
}

function closeDiv(): void {
    echo '</div>';
}

function makeHead(string $path,string $title): void {

    if (!file_exists($path)) {
        echo "CSS File niet gevonden";
        exit;
    }

    echo '<head><link rel="stylesheet" href="'. $path . '"><title>'. $title . '</title></head>';
}

function showTitle(string $title,string $class = ''): void {
    echo '<h1' . ($class ? ' class="' . $class . '"' : '') . '>' . $title . '</h1>';
}

function makeFooter(): void {
    echo '<footer>&copy ' . date("Y") . ' Saman Ahmad</footer>';
}

function showMessage(string $message, string $class = ''): void {
    echo '<p' . ($class ? ' class="' . $class . '"' : '') . '>' . $message . '</p>';
}

function makeActionField (string $page,string $class = ''): void {
    echo '<a href="index.php?page=' . strtolower($page) . '"' . ($class ? ' class="' . $class . '"' : '') . '>';
}

function closeActionField(): void {
    echo '</a>';
}

function openForm (string $class = ''): void {
    echo '<form' . ($class ? ' class="' . $class . '"' : '') . ' method="post" action="index.php">';
}

function closeForm (): void {
    echo '</form>';
}

function assignHiddenType(array $fields): void {
    foreach ($fields as $name => $value) {
        echo '<input type="hidden" name="' . $name . '" value="' . $value . '">';
    }
}

//        [
//            'name' => '',
//            'desc' => '',
//            'value' => '',
//            'class' => ''
//        ],
function makeButton(array $button): void {
    echo '<button type="submit"' . 
    (!empty($button['name']) ? ' name="' . $button['name'] . '"' : '') .
    (!empty($button['value']) ? ' value="' . $button['value'] . '"' : '') .
    (!empty($button['class']) ? ' class="' . $button['class'] . '"' : '') . '>' .
    ($button['desc'] ?? '') . '</button>';
}


function newLine (): void {
    echo '<br>';
}

function openUnorderedList(string $class = '') {
    echo '<ul' . ($class ? ' class="' . $class . '"' : '') . '>';
}

function closeUnorderedList(): void {
    echo '</ul>';
}

function openList(): void {
    echo '<li>';
}

function closeList(): void {
    echo '</li>';
}

function openTable(string $class = ''): void {
    echo '<table' . ($class ? ' class="' . $class . '"' : '') . '>';
}

function closeTable(): void {
    echo '</table>';
}

function openTableBody(): void {
    echo '<tbody>';
}

function closeTableBody(): void {
    echo '</tbody>';
}

function openTableRow(): void {
    echo '<tr>';
}

function closeTableRow(): void {
    echo '</tr>';
}

function makeTableHeader(array $headers): void {
    echo '<thead>';
    openTableRow();
    foreach ($headers as $header) {
        echo '<th>' . $header . '</th>';
    }
    closeTableRow();
    echo '</thead>';
}

function makeTableData(string $data = '',string $class = '',string $span = '',array $item = []): void {
    echo '<td' . ($class ? ' class="' . $class . '"' : '') . '>';
    if(!empty($item)) loadImage($item['image_path'], $item['name']);
    echo $data;
    if(!empty($span)) echo '<span>' . $span . '</span>';
    echo '</td>';
}

function showSpan (string $msg,string $class = ''): void {
    echo '<span' . ($class ? ' class="' . $class . '"' : '') . '>' . $msg . '</span>';
}

//Used for the cart table
function makeSummaryRow(string $label, float $amount, string $class = ''): void {
    openDiv($class);
    showSpan($label);
    showSpan('€' . number_format($amount, 2, ',', '.'));
    closeDiv();
}

function loadImage(string $path,string $name,string $class = ''): void {
    echo '<img src="images/' . $path . '" alt="'  . $name . '"' . ($class ? ' class="' . $class . '"' : '') . '>';
}

//Makes the fields used in a form
function makeFields(bool $empty, string $field, string $info): void {
    $inputClass = $empty ? 'error' : '';

    if (str_contains($field,'wachtwoord')) {
        echo '<input type="password" class="' . $inputClass . '" name="' . $field . '" value="' . $info . '" autocomplete="new-password">';
    } elseif (str_contains($field,'amount')) {
        echo '<input type="number" class="' . $inputClass . '" name="' . $field . '" min="1" value="1">';        
    } else {
        echo '<input type="text" class="' . $inputClass . '" name="' . $field . '" value="' . $info . '">';
    }
}

//--------------HTML BUILDER functions
//Show list of options based on $option
function showListOfOptions(string $class = '',array $options): void {
    openUnorderedList($class);

    foreach ($options as $option) {

        openList();
        makeActionField($option);

        if (!empty($_SESSION['logged_in']) && $option === 'LOGOUT') echo $option . ' ' . htmlspecialchars($_SESSION['username'], ENT_QUOTES);
        else echo $option;

        echo closeActionField();
        closeList();
    }
    closeUnorderedList();
}

//Build a form with endless amount of options, below is every possible argument that is used
// This for is used for Contact, Register, Login
//    showForm([
//        'class'       => ?? '',
//        'formTitle'   => ?? '',
//        'fields'      => [],
//        'hidden'      => ['name' => 'value'], //can contain multiple entries
//        'post'        =>  ?? [],
//        'emptyFields' =>  ?? [],
//        'button'      => [
//            'name' => '',
//            'desc' => '',
//            'value' => '',
//            'class' => ''
//        ],
//        'showLabel'   => ?? false,
//        'addNewline'   => ?? false
//    ]);
function showForm(array $config): void {
    openForm($config['class'] ?? '');

    $builder = '';
    if(!empty($config['formTitle'])) $builder = '<legend>' . showTitle($config['formTitle']) . '</legend>';
    echo $builder;

    assignHiddenType($config['hidden']);

    foreach ($config['fields'] as $field) {
        $cleanedField = slugify($field);
        if(!empty($config['emptyFields'])) $isEmpty = in_array($cleanedField, $config['emptyFields']);

        if($config['showLabel'] ?? false) echo '<label for="' . $cleanedField . '">' . $field . ':</label>';

        makeFields($isEmpty ?? false,$cleanedField,htmlspecialchars(isset($config['post'][$cleanedField]) ? $config['post'][$cleanedField] : '',  ENT_QUOTES));

        if ($isEmpty ?? false) {
            showSpan('Veld is leeg!','error');
        }

        if($config['addNewline'] ?? false) newLine();
    }

    makeButton($config['button']);
    closeForm();
}

// Function used to show products on the webshop page
function showProducts(array $productsToShow): void {
    openDiv('page');
    foreach($productsToShow as $p) {
        openDiv('card');
        
        makeActionField('product&id=' . (int)$p['id'] . '"', 'card-link');
        loadImage($p['image_path'],$p['name']);
        openDiv('card-content');
        showMessage($p['name'] . ' - €' . number_format($p['price'], 2, ',', ''));
        closeDiv();
        closeActionField();

        openDiv('actions');
        if(!empty($_SESSION['logged_in'])) {

            showForm([
                'fields' => ['amount'],
                'button' => [
                    'desc' => 'Bestel Nu!'
                ],
                'hidden' => [
                    'page' => 'order',
                    'item_id' => (int)$p['id']
                ]]);
            
        } else {
            makeActionField('login');
            echo 'Login om te bestellen!';
            closeActionField();
        }

        closeDiv();
        closeDiv();
    }
    closeDiv();
}

//The pagination needed if products are more than what can be posted on the page
function showPagination(int $currentPage, int $totalPages): void {
    openDiv('pagination-container');

    openForm();
    assignHiddenType(['page' => 'webshop']);

    openDiv('pagination');
    
    for ($i = 1; $i <= $totalPages; $i++) {
        $activeClass = $i === $currentPage ? ' active' : '';

        makeButton([
            'name' => 'webshoppage',
            'desc' => $i,
            'value' => $i,
            'class' => 'page-btn' . $activeClass,
        ]);
    }
    
    closeDiv();
    closeForm();
    closeDiv();
}

//Function used to show detailed information of a product
function showProductDetail(array $product): void {
    openDiv('product-detail');
    
    openDiv('product-image-section');
    loadImage($product['image_path'], $product['name']);
    closeDiv();
    
    openDiv('product-info-section');
    showTitle($product['name']);
    
    showMessage('€' . number_format($product['price'], 2, ',', ''),'product-price');
    if (!empty($product['description'])) showMessage(htmlspecialchars($product['description']),'product-description');
    
    if(!empty($_SESSION['logged_in'])) {

        showForm( [            
            'class' => 'product-order-form',
            'fields' => ['amount'],
            'button' => [
                'desc' => 'Bestel Nu!'
            ],
            'hidden' => [
                'page' => 'order',
                'id' => (int)$product['id'],
                'item_id' => (int)$product['id']
            ]]);
    } else {
        makeActionField('login','login-prompt');
        echo 'Login om te bestellen!';
        closeActionField();
    }
    
    closeDiv();
    closeDiv();
}

//Function used to show the cart page
function showCart(array $cartItems,float $shipping): void {
    $total = 0;

    openDiv('cart-wrapper');
    showTitle('Winkel mandje');

    openDiv('cart-container');
    openDiv('cart-items');
    
    openTable('cart-table');
    makeTableHeader(['Product', 'Prijs', 'Aantal', 'Subtotaal']);
    openTableBody();
    
    foreach ($cartItems as $item) {
        $subtotal = $item['price'] * $item['amount'];
        $total += $subtotal;
        
        openTableRow();

        makeTableData('', 'cart-product', htmlspecialchars($item['name']), $item);
        makeTableData('€' . number_format($item['price'], 2, ',', '.'));
        makeTableData((string)(int)$item['amount']);
        makeTableData('€' . number_format($subtotal, 2, ',', '.'));
        
        closeTableRow();
    }
    
    closeTableBody();
    closeTable();
    closeDiv();

    openDiv('cart-summary');

    showTitle('Totalen');
    makeSummaryRow('Subtotaal', $total,'summary-row');
    makeSummaryRow('Shipping', $shipping,'summary-row');
    $total += $shipping;
    makeSummaryRow('Totaal', $total, 'summary-total');

    showForm([
        'fields' => [],
        'button' => [
            'desc' => 'Proceed to checkout',
            'class' => 'checkout-btn'
        ],
        'hidden' => ['page' => 'cart'],
        'class' => 'checkout-form'
    ]);

    closeDiv();
    closeDiv();
    closeDiv();
}


//--------------LOGIC functions

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

//Function to check whether the given request to the server is a POST or GET
//It also returns the page-key that the request was originated from
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

//Append newly registered user to database
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

//Fetch all items from database
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

//Fetch all information of an item for product page
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

//Combine items and orders to figure out how much of what has been ordered
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

//Append an order to the database, first to orders then order_item
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
        $result['message'] = 'Order geplaatst! Order #' . $orderId;
        unset($_SESSION['orders']);
        
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        if (isset($stmt)) mysqli_stmt_close($stmt);
        $result['message'] = 'Error adding order: ' . $e;
    }
    
    return $result;
}
?>