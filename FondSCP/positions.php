<?php
require_once 'check_auth.php';
require_once 'config.php';

$positions = [];

try {
    $sql = "SELECT * FROM positions ORDER BY id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $positions = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
} catch (PDOException $e) {
    error_log('Ошибка запроса к БД (positions): ' . $e->getMessage());
    $positions = [];
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Кунсткамера - Должности</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
            min-height: 100vh;
            background-image: url('https://cs1.gtavicecity.ru/screenshots/9a0d4/2021-07/original/e71b6fb3e3b577f2ed72dbafc7414c9c4a47527c/935873-gallery11.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            position: relative;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

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
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .nav-btn.active {
            background: rgba(255, 255, 255, 0.3);
            border: 1px solid white;
            cursor: default;
        }

        .table-container {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        thead {
            background-color: #2c3e50;
            color: white;
        }

        th {
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 0.95rem;
        }

        tbody tr {
            border-bottom: 1px solid #ecf0f1;
            transition: background-color 0.2s;
        }

        tbody tr:hover {
            background-color: #f8f9fa;
        }

        td {
            padding: 12px;
            color: #2c3e50;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-style: italic;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #95a5a6;
            font-size: 0.9rem;
            border-top: 1px solid #ecf0f1;
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
                <a href="index.php" class="nav-btn">Игровой Персонаж</a>
                <a href="dangers.php" class="nav-btn">Уровни опасности</a>
                <a href="positions.php" class="nav-btn active">Должности</a>
            </div>
            <h1>ДОЛЖНОСТИ КУНСТКАМЕРЫ</h1>
            <p>Список всех должностей и их описаний</p>
        </div>

        <div class="table-container">
            <?php if (!empty($positions)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Должность</th>
                            <th>Описание</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($positions as $position): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($position['id'] ?? '—'); ?></td>
                                <td><?php echo htmlspecialchars($position['position'] ?? '—'); ?></td>
                                <td><?php echo htmlspecialchars($position['description'] ?? '—'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>Данные в таблице "positions" отсутствуют.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="footer">
            <!-- &copy; 2024 Кунсткамера. Все права защищены. -->
        </div>
    </div>
</body>
</html>
