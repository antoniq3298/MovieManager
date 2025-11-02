<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $title = trim($_POST['title']);
    $genre = trim($_POST['genre']);
    $year = intval($_POST['year']);
    $user_id = $_SESSION['user_id'];

    if(!empty($title)){
        $stmt = $conn->prepare("INSERT INTO movies (title, genre, year, user_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $title, $genre, $year, $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: movies.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="bg">
<head>
<meta charset="UTF-8">
<title>Добави филм</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="btn">
  <h2>➕ Добави нов филм</h2>
  <form method="POST">
    <label>Заглавие:</label>
    <input type="text" name="title" required>
    <label>Жанр:</label>
    <input type="text" name="genre">
    <label>Година:</label>
    <input type="number" name="year" min="1900" max="2100">
    <button type="submit" class="btn">Добави</button>
  </form>
  <a href="movies.php" class="btn secondary">⬅ Назад</a>
</div>
</body>
</html>
