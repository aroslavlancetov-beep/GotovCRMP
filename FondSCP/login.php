<?php
session_start();
require_once 'config.php';

$error = '';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Заполните все поля';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :username");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Неверное имя пользователя или пароль';
            }
        } catch (PDOException $e) {
            $error = 'Ошибка базы данных';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>вход</title>
    <style>
        body {
            background-image: url('https://cs1.gtavicecity.ru/screenshots/9a0d4/2021-07/original/e71b6fb3e3b577f2ed72dbafc7414c9c4a47527c/935873-gallery11.jpg');
            background-size: cover;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
        }
        .container {
            background: rgb(255,255,255);
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>вход</h1>
        
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <form method="POST">
            <p>
                <label>имя или почта:</label><br>
                <input type="text" name="username" required style="width:100%; padding:5px;">
            </p>
            <p>
                <label>пароль:</label><br>
                <input type="password" name="password" required style="width:100%; padding:5px;">
            </p>
            <p>
                <button type="submit">войти</button>
            </p>
        </form>
        
        <p><a href="register.php">регистрация</a></p>
    </div>
</body>
</html>