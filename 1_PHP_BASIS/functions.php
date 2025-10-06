<?php
//Open HTML
function openHTML(): void {
    echo '<html>';
}

//Makes the head part of the HTML document
//$path is a string to the path of the CSS-file
//$title is a string that is the title of the page
function makeHead($path, $title): void {
    echo '<head>';

    if (!file_exists($path)) {
        echo "CSS File niet gevonden";
        exit;
    }

    echo '<link rel="stylesheet" href="'. $path . '">';
    echo '<title>'. $title . '</title>';
    echo '</head>';
}

//Open body
function openBody(): void {
    echo '<body>';
}

//Show title
//$title is the title that you want to show on the page
//$class is the style-class you want to use for the title
function showTitle($title, $class): void {
    echo '<h1 class="'. $class . '">' . $title . '</h1>';
}

//Show list of options
//$class is the style-class you want to use for the list
//$options is an array of strings that are the options
function showListOfOptions($class, $options): void {
    echo '<ul class="'. $class . '">';

    foreach ($options as $option) {

        $builder = '<li><a href="index.php?page='. strtolower($option) .'">' . $option;

        if(isset($_SESSION['logged_in']) && !strcmp('LOGOUT',$option))  $builder .= ' ' . $_SESSION['username'];

        $builder .= '</a></li>';
        echo $builder;
    }
    echo '</ul>';
}

//Show message
//$message is the message you want to present in the body
function showMessage($message): void {
    echo '<p>' . $message . '</p>';
}

//Validate input of form by checking if they are empty
//Option to show the input if $showInput is true
//return true if all fields are filled else return false
function inputValidationForm($post) : array {
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
function showForm($class,$formTitle,$fields,$action,$post = [],$emptyFields = []): void {
    echo '<form class="' . $class . '" method="post" action="index.php">';
    echo '<legend><h2>' . $formTitle . ':</h2></legend>';
    echo '<input type="hidden" name="page" value="' . $action . '">';

    foreach ($fields as $field) {
        $cleanedField = slugify($field);

        $info = $post[$cleanedField] ?? '';
        $isEmpty = in_array($cleanedField, $emptyFields);

        echo '<label for="' . $cleanedField . '">' . $field . ':</label>';

        if (str_contains($cleanedField,'wachtwoord')) {
            echo '<input type="password" id="' . $cleanedField . '" name="' . $cleanedField . '" value="' . $info . '" autocomplete="new-password">';
        } else {
            echo '<input type="text" id="' . $cleanedField . '" name="' . $cleanedField . '" value="' . $info . '">';
        }

        if ($isEmpty) {
            echo '<span>Veld is leeg!</span>';
        }

        echo '<br>';
    }

    echo '<input type="submit" value="Verstuur">';
    echo '</form>';
}

//Read the file that contains all the user data
//$path is path to this file
function readUserFile($path): array {
    $handle = fopen($path,'r');
    $rows = [];

    while (($line = fgets($handle)) !== false) {
        $line = trim($line);                   
        if ($line !== '') {
            $fields = explode('|', $line);      
            $rows[] = $fields;                  
        }
    }
    return $rows;
}

//Append newly registered user to the file
//$path is the path to the users file
//$submitted is the POST data that was submitted
function appendFile($path,$data): void {
    $handle = fopen($path, 'a');

    if ($handle) {
        $line = implode('|', $data) . "\n";
        fwrite($handle, $line);
        fclose($handle);
    } 
}

//Check whether the e-mail is in the database
//$data is the database of users
//$email is the e-mail adress
//returns the row of data and also a boolean whether email was found or not
function checkEmail($data,$email): array {
    foreach ($data as $row) {
        if (isset($row[0]) && strcasecmp($row[0], $email) === 0) {
            return [$row,true];
        }
    }

    return [$row,false];
}

//Handle the input of the login
//$path is the path to the user file
//returns a message regarding how the login was handled
function handleLogin($path,$post) : string {
    $foundEmail = checkEmail(readUserFile($path),$post['email']);

    if(!$foundEmail[1]) {
        return 'E-mail niet gevonden';
    }

    $row = $foundEmail[0];
    if (isset($row[1]) && isset($row[2])) {
        $actualPassword = $row[2];

        if ($actualPassword === $post['wachtwoord']) {
            $_SESSION['username'] = $row[1];
            $_SESSION['logged_in'] = true;
            return 'Login succesvol';
        } else {
            return 'Incorrecte wachtwoord';
        }
    }
    return 'Fout in array';
}

//Handles the input from register
//$path is the path to the users file
//Returns whether register was successful as a boolean and also a message that contains an error message or success message
function handleRegister($path,$post): array {
    $results = [
        'message' => '',
        'validated' => false
    ];

    if(checkEmail(readUserFile($path),$post['email'])[1]) {
        $results['message'] = 'E-mail is al geregisteerd';
        return $results;
    }

    if(!strcmp($post['wachtwoord'],$post['herhaalwachtwoord'])) {
        $toSave = $post;
        unset($toSave['herhaalwachtwoord'], $toSave['page']);

        $email = $toSave['email'];
        $name  = $toSave['naam'];
        unset($toSave['email'], $toSave['naam']);
        $toSave = array_merge(['email' => $email, 'naam' => $name], $toSave);

        appendFile($path,$toSave);
        $results['message'] = 'Succesvol geregisteerd!!!';
        $results['validated'] = true;
    } else {
        $results['message'] = 'Wachtwoorden komen niet overeen';
    }

    return $results;
}

//Close body
function closeBody(): void {
    echo '</body>';
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
function slugify($text) {
    $text = strtolower(trim($text));
    $text = str_replace([' ', '-', '_'], '', $text);
    $text = preg_replace('/[^a-z0-9]/', '', $text);
    return $text;
}

//Function to validate the request received by the server
function validateRequest(array $request) : array
{
    $result = $request;

    //Initialize keys to process request
    $result['message'] = '';
    $result['validated'] = false;
    $result['empty'] = [];

    //POST
    if ($request['posted'])
    {
        $result['empty'] = inputValidationForm($_POST);

        if(empty($result['empty'])) {
            switch ($request['page'])
            {
                case 'login':
                    $result['message'] = handleLogin($request['userDataPath'], $_POST);
                    break;
                case 'register':
                    $result = array_merge($result, handleRegister($request['userDataPath'], $_POST));
                    break;
                default:
                    $result['validated'] = true;
            }
        }
    //GET where the request needs to be validated
    } else {
        switch ($request['page'])
        {
            case 'logout':
                if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                    session_unset();
                    session_destroy();
                    unset($result['menu']['LOGOUT']);
                }
                $result['page'] = 'login';
                break;
            default:
                break;
        }
    }
    
    //Check if page is allowed otherwise return to home
    if (!in_array($result['page'],array_map('strtolower', $result['menu']))) $result['page'] = 'home';

    //Remove LOGIN and REGISTER if user logged in
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        $result['menu'] = array_diff($request['menu'], ['LOGIN', 'REGISTER']);
        $result['menu'][] = 'LOGOUT';
    }

    return $result;
} 

//Function to include files that build the page
function showResponse(array $response)
{
    include 'header.php';
    include 'pages/' . $response['page'] . '.php';
    include 'footer.php';
} 
?>