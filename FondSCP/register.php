<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Заполните все поля';
    } elseif (strlen($username) < 3) {
        $error = 'Имя пользователя минимум 3 символа';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Введите корректный email';
    } elseif (strlen($password) < 4) {
        $error = 'Пароль минимум 4 символа';
    } elseif ($password !== $confirm_password) {
        $error = 'Пароли не совпадают';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
            $stmt->execute([':username' => $username, ':email' => $email]);
            if ($stmt->fetch()) {
                $error = 'Пользователь уже существует';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                $stmt->execute([
                    ':username' => $username,
                    ':email' => $email,
                    ':password' => $hashed_password
                ]);
                $success = 'Регистрация успешна! Теперь можете войти.';
                $username = $email = '';
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
    <title>регистрация</title>
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
        <h1>регистрация</h1>
        
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        
        <form method="POST">
            <p>
                <label>имя пользователя:</label><br>
                <input type="text" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required style="width:100%; padding:5px;">
            </p>
            <p>
                <label>почта:</label><br>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required style="width:100%; padding:5px;">
            </p>
            <p>
                <label>пароль:</label><br>
                <input type="password" name="password" required style="width:100%; padding:5px;">
            </p>
            <p>
                <label>подтвердите пароль:</label><br>
                <input type="password" name="confirm_password" required style="width:100%; padding:5px;">
            </p>
            <p>
                <button type="submit">зарегистрироваться</button>
            </p>
        </form>
        
        <p><a href="login.php">войти</a></p>
    </div>
</body>
</html>