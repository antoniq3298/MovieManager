<?php
session_start();
?>
<!DOCTYPE html>
<html lang="bg">
<head>
<meta charset="UTF-8">
<title>Movie Manager</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="home">
  <div class="overlay">
    <h1>🎬 Добре дошли в Movie Manager</h1>
    <div class="buttons">
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="movies.php" class="btn">➡ Моите филми</a>
        <a href="logout.php" class="btn secondary">🚪 Изход</a>
      <?php else: ?>
        <a href="login.php" class="btn">Вход</a>
        <a href="register.php" class="btn secondary">Регистрация</a>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
