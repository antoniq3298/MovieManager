<?php
// Включваме показване на всички PHP грешки
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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
    echo "<h3 style='color:red;text-align:center;'>Филмът не е намерен!</h3>";
    exit();
}

// Обработка на POST заявка за редакция
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title']);
    $genre = trim($_POST['genre']);
    $year = intval($_POST['year']);

    $stmt = $conn->prepare("UPDATE movies SET title=?, genre=?, year=? WHERE id=? AND user_id=?");
    if(!$stmt){
        die("SQL Prepare Error: " . $conn->error);
    }
    $stmt->bind_param("ssiii", $title, $genre, $year, $movie_id, $user_id);
    $stmt->execute();

    if($stmt->error){
        echo "<p style='color:red;text-align:center;'>SQL ERROR: " . htmlspecialchars($stmt->error) . "</p>";
    } else {
        if($stmt->affected_rows > 0){
            // Успешно обновяване — пренасочване
            header("Location: movies.php");
            exit();
        } else {
            echo "<p style='color:orange;text-align:center;'>Няма промени или записът не беше намерен. Провери id и user_id.</p>";
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="bg">
<head>
<meta charset="UTF-8">
<title>Редактирай филм</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container small">
  <h2>✏️ Редактирай филм</h2>
  <form method="POST">
    <label>Заглавие:</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>" required>
    <label>Жанр:</label>
    <input type="text" name="genre" value="<?php echo htmlspecialchars($movie['genre']); ?>">
    <label>Година:</label>
    <input type="number" name="year" value="<?php echo htmlspecialchars($movie['year']); ?>" min="1900" max="2100">
    <button type="submit" class="btn">💾 Запази</button>
  </form>
  <a href="movies.php" class="btn secondary">⬅ Назад</a>
</div>
</body>
</html>
