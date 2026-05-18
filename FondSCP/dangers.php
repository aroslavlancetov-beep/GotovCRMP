<?php
require_once 'check_auth.php';
// dangers.php
require_once 'config.php';

$dangers = [];
$error_message = '';

try {
    $sql = "SELECT * FROM dangers ORDER BY id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $dangers = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
} catch (PDOException $e) {
    error_log('Ошибка запроса к БД (dangers): ' . $e->getMessage());
    $error_message = 'Произошла ошибка при загрузке данных об уровнях опасности. Администраторы уведомлены.';
    $dangers = [];
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кунсткамера - Уровни опасности</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
            min-height: 100vh;
            background-image: url('https://cs1.gtavicecity.ru/screenshots/9a0d4/2021-07/original/e71b6fb3e3b577f2ed72dbafc7414c9c4a47527c/935873-gallery11.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        /* Оригинальная шапка с коричневым градиентом */
        .header {
            background: linear-gradient(135deg, #cab27d 0%, #36230a 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .nav-buttons {
            position: absolute;
            top: 20px;
            right: 30px;
            display: flex;
            gap: 15px;
        }

        .nav-btn {
            display: inline-block;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .nav-btn:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .nav-btn.active {
            background: rgba(255, 255, 255, 0.3);
            border: 1px solid white;
            cursor: default;
        }

        .content {
            padding: 30px;
        }

        /* Новые цвета: тёмно-синие и серые */
        .danger-level {
            margin-bottom: 30px;
            padding: 20px;
            border-left: 4px solid #142f53; /* тёмно-синий */
            background: #f0f7ff; /* светло-голубой фон */
            border-radius: 0 4px 4px 0;
        }

        .danger-level h2 {
            color: #3c5e80; /* спокойный синий */
            margin-bottom: 10px;
            font-size: 1.4rem;
        }

        .error {
            background: #e9ecef; /* светло-серый */
            color: #6c757d; /* серый текст */
            padding: 20px;
            border-left: 4px solid #6c757d; /* серая линия */
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d; /* серый */
            font-style: italic;
            background: #f8f9fa; /* очень светлый серый */
            border-radius: 4px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #6c757d; /* серый */
            font-size: 0.9rem;
            border-top: 1px solid #e9ecef; /* тонкая серая линия */
            background: #f8f9fa; /* светлый фон */
        }

        @media (max-width: 768px) {
            .nav-buttons {
                position: static;
                justify-content: center;
                margin-top: 15px;
            }
            
            .header {
                padding-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="nav-buttons">
                <a href="index.php" class="nav-btn">Игровой персонаж</a>
                <a href="dangers.php" class="nav-btn active">Уровни опасности</a>
            </div>
            
            
        </div>

        <div class="content">
            <?php if ($error_message): ?>
                <div class="error">
                    <p><?php echo htmlspecialchars($error_message); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($dangers)): ?>
                <?php foreach ($dangers as $level): ?>
                    <div class="danger-level">
                <h2>
                    <?php
            // Гибкое отображение названия уровня
            echo htmlspecialchars(
                $level['name'] ??
                $level['class_name'] ??
                $level['title'] ??
                'Без названия'
            );
            ?>
        </h2>
        <p>
            <?php
            // Гибкое отображение описания уровня
            echo htmlspecialchars(
                $level['description'] ??
                $level['desc'] ??
                $level['info'] ??
                'Описание отсутствует'
            );
            ?>
        </p>
    </div>
<?php endforeach; ?>
<?php else: ?>
    <div class="no-data">
        <p>В базе данных пока нет информации об уровнях опасности.</p>
    </div>
<?php endif; ?>
</div>

<div class="footer">

</div>
</div>