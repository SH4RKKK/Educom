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
$userModel->registerUser('Some body', 'some@body.com', '123');
echo $userModel->getError() ?: 'User registered successfully<br>';

// Test loginUser()
echo '<h3>Login User</h3>';
$userModel->loginUser('some@body.com', '123');
echo $userModel->getError() ?: 'Login successful. User ID: ' . $_SESSION['user_id'] . ', Name: ' . $_SESSION['username'] . '<br>';