<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'config.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === "" || $password === "") {
        $message = "Попълнете всички полета!";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $hashed);
            $stmt->fetch();
            if (password_verify($password, $hashed)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                header("Location: movies.php"); // директно към dashboard
                exit();
            } else {
                $message = "Невалидна парола!";
            }
        } else {
            $message = "Потребителят не е намерен!";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - Movie Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login">
    <div class="container small">
        <h2>🎬 Вход в Movie Manager</h2>
        <!-- Съобщение за грешка, ако има -->
        <?php if(isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>
        <form method="POST" action="login.php">
            <label>Потребителско име</label>
            <input type="text" name="username" placeholder="Въведи потребителско име" required>

            <label>Парола</label>
            <input type="password" name="password" placeholder="Въведи парола" required>

            <button type="submit" class="btn">Вход</button>
        </form>
        <a href="register.php" class="btn secondary">Регистрация</a>
    </div>
</body>
</html>

