<?php
session_start();


$users = [
    'user1' => 'password1',
    'user2' => 'password2'
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    if (isset($users[$username]) && $users[$username] === $password) {
        $_SESSION['username'] = $username;
        header('Location: main.html');
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>
