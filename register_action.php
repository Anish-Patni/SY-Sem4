<?php
session_start();


$users = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "All fields are required.";
    } elseif ($password !== $confirm_password) {
        echo "Passwords do not match.";
    } elseif (array_key_exists($username, $users)) {
        echo "Username already exists.";
    } else {
        $users[$username] = password_hash($password, PASSWORD_DEFAULT);
        echo "Registration successful! You can now log in.";
        header('Location: Login.html');
        exit();
    }
}
?>
