<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    if ($password !== $confirm) {
        $message = "Паролите не съвпадат.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['username'] = $username;
            header("Location: movies.php");
            exit();
        } else {
            $message = "Потребителското име вече съществува.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="bg">
<head>
<meta charset="UTF-8">
<title>Регистрация</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container small">
  <h2>Регистрация</h2>
  <?php if($message) echo "<p class='error'>$message</p>"; ?>
  <form method="POST">
    <label>Потребителско име</label>
    <input type="text" name="username" required>
    <label>Парола</label>
    <input type="password" name="password" required>
    <label>Повтори паролата</label>
    <input type="password" name="confirm" required>
    <button type="submit" class="btn">Регистрация</button>
  </form>
  <p>Вече имаш акаунт? <a href="login.php">Вход</a></p>
</div>
</body>
</html>
