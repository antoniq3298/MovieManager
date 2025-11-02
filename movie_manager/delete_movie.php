<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Вземаме id от GET
$movie_id = intval($_GET['id'] ?? 0);
$user_id = $_SESSION['user_id'];

// Проверка дали филмът съществува
$stmt = $conn->prepare("SELECT * FROM movies WHERE id=? AND user_id=?");
if(!$stmt){
    die("SQL Prepare Error: " . $conn->error);
}
$stmt->bind_param("ii", $movie_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();
$stmt->close();

if(!$movie){
    echo "<h3 style='color:red;text-align:center;'>Филмът не е намерен или вече е изтрит!</h3>";
    echo '<p style="text-align:center;"><a href="movies.php" class="btn secondary">⬅ Назад</a></p>';
    exit();
}

// Изтриваме филма
$stmt = $conn->prepare("DELETE FROM movies WHERE id=? AND user_id=?");
if(!$stmt){
    die("SQL Prepare Error: " . $conn->error);
}
$stmt->bind_param("ii", $movie_id, $user_id);
$stmt->execute();

if($stmt->error){
    echo "<p style='color:red;text-align:center;'>SQL ERROR при изтриване: " . htmlspecialchars($stmt->error) . "</p>";
} else {
    header("Location: movies.php");
    exit();
}
$stmt->close();
?>
