<?php
// add_message.php
session_start();
include 'db.php';

$user_name = $_POST['user_name'];
$email = $_POST['email'];
$text = $_POST['text'];
$captcha = $_POST['captcha'];

// Проверка CAPTCHA
if ($captcha !== $_SESSION['captcha']) {
    die('Invalid CAPTCHA');
}

// Валидация данных
if (!preg_match('/^[a-zA-Z0-9]+$/', $user_name)) {
    die('Invalid user name');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('Invalid email');
}
if (empty($text)) {
    die('Message text is required');
}

$ip_address = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

$stmt = $conn->prepare("INSERT INTO messages (user_name, email, text, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $user_name, $email, $text, $ip_address, $user_agent);
$stmt->execute();

$stmt->close();
$conn->close();

header('Location: index.php');
?>