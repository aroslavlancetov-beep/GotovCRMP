<?php
require_once 'check_auth.php';
require_once 'config.php';
        
$names = [];

try {
    $sql = "SELECT n.*, d.class_name as danger_name, s.skill_name as skill_name 
            FROM names n
            LEFT JOIN dangers d ON n.descriptions = d.id
            LEFT JOIN skills s ON n.skill_id = s.id
            ORDER BY n.id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $names = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
} catch (PDOException $e) {
    error_log('Ошибка запроса к БД (names): ' . $e->getMessage());
    $names = [];
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Кунсткамера - Сущности</title>
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
            padding: 6px 12px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-size: 12px;
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

        .edit-nav-btn {
            color: #000000 !important;
        }

        .edit-nav-btn:hover {
            color: #000000 !important;
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

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            margin: 0 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #28a745;
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
                <a href="index.php" class="nav-btn active">Игровой Персонаж</a>
                <a href="dangers.php" class="nav-btn">Уровни опасности</a>
                <a href="positions.php" class="nav-btn">Должности</a>
                <a href="logout.php" class="nav-btn">Выйти</a>
            </div>
            <h1>РАЗЫСКИВАЮТСЯ</h1>
            <p>Список персонажей и их уровни опасности CRMP проектов</p>
        </div>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="success-message">
                Персонаж успешно добавлен в базу данных
            </div>
        <?php endif; ?>

        <div class="table-container">
            <?php if (!empty($names)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя персонажа</th>
                            <th>Псевдоним</th>
                            <th>Дата обнаружения</th>
                            <th>Уровень опасности</th>
                            <th>Навык</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($names as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['object_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['alias'] ?? '—'); ?></td>
                                <td><?php echo htmlspecialchars($row['discovery_date'] ?? '—'); ?></td>
                                <td><?php echo htmlspecialchars($row['danger_name'] ?? '—'); ?></td>
                                <td><?php echo htmlspecialchars($row['skill_name'] ?? '—'); ?></td>
                                <td>
                                    <a href="view.php?id=<?php echo $row['id']; ?>" class="nav-btn edit-nav-btn">Просмотр</a>
                                    <a href="editor.php?id=<?php echo $row['id']; ?>" class="nav-btn edit-nav-btn">Редактировать</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>Данные в таблице "names" отсутствуют.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="footer">
            <!-- футер -->
        </div>
    </div>

    <div style="margin-bottom: 20px; text-align: center; margin-top: 20px;">
        <a href="add_person.php" class="nav-btn">Добавить персонажа</a>
    </div>

    <a href="https://vk.ru/video-155939640_456239467?access_key=35e4816f64ec80e260" style="display: block; text-align: center; margin-top: 20px; color: #e1e1e4; text-decoration: none;">
        Если вы обнаружили новый тип опасности | Свяжитесь с нами: 392e96alr0t@emailax.pro
    </a>
</body>
</html>