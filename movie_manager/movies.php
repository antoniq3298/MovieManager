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

$user_id = $_SESSION['user_id'];

// Вземаме всички филми на текущия потребител
$stmt = $conn->prepare("SELECT * FROM movies WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$movies = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="bg">
<head>
<meta charset="UTF-8">
<title>Моите филми</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h2>🎥 Моите филми</h2>
  <a href="add_movie.php" class="btn">➕ Добави нов филм</a>
  <a href="logout.php" class="btn secondary">🚪 Изход</a>
  
  <?php if(count($movies) === 0): ?>
    <p class="empty">Все още няма добавени филми.</p>
  <?php else: ?>
    <div class="grid">
      <?php foreach($movies as $movie): ?>
        <div class="card">
          <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
          <p><strong>Жанр:</strong> <?php echo htmlspecialchars($movie['genre']); ?></p>
          <p><strong>Година:</strong> <?php echo htmlspecialchars($movie['year']); ?></p>
          <div class="card-buttons">
            <a href="edit_movie.php?id=<?php echo $movie['id']; ?>" class="btn small">✏️ Редактирай</a>
            <a href="delete_movie.php?id=<?php echo $movie['id']; ?>" class="btn danger small" onclick="return confirm('Сигурни ли сте, че искате да изтриете този филм?')">🗑️ Изтрий</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
