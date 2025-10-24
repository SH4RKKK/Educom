<?php
// test_user_model.php
session_start();
require_once '../database/Database.php';
require_once '../abstract/Model.php';
require_once '../models/UserModel.php';

$database = new Database('localhost', 'website', 'root', '');
$userModel = new UserModel($database);

echo '<h2>UserModel Tests</h2>';

// Test registerUser()
echo '<h3>Register User</h3>';
$registerResult = $userModel->registerUser('Some body', 'some@body.com', '123');

if ($registerResult['success']) {
    echo 'Success: ' . $registerResult['message'] . '<br>';
} else {
    echo 'Error: ' . $registerResult['message'] . '<br>';
}

// Test loginUser()
echo '<h3>Login User</h3>';
$loginResult = $userModel->loginUser('some@body.com', '123');

if ($loginResult['success']) {
    echo 'Success: ' . $loginResult['message'] . '<br>';
    // Set session manually in test (normally done in controller)
    $_SESSION['user_id'] = $loginResult['user']['id'];
    $_SESSION['username'] = $loginResult['user']['name'];
    $_SESSION['logged_in'] = true;
    echo 'User ID: ' . $_SESSION['user_id'] . ', Name: ' . $_SESSION['username'] . '<br>';
} else {
    echo 'Error: ' . $loginResult['message'] . '<br>';
}

// Test loginUser() with wrong password
echo '<h3>Login User (Wrong Password)</h3>';
$loginResult = $userModel->loginUser('some@body.com', 'wrongpassword');

if ($loginResult['success']) {
    echo 'Unexpected success<br>';
} else {
    echo 'Expected error: ' . $loginResult['message'] . '<br>';
}

// Test loginUser() with non-existent email
echo '<h3>Login User (Non-existent Email)</h3>';
$loginResult = $userModel->loginUser('nonexistent@email.com', '123');

if ($loginResult['success']) {
    echo 'Unexpected success<br>';
} else {
    echo 'Expected error: ' . $loginResult['message'] . '<br>';
}