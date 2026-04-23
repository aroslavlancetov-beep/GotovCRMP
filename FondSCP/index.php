<?php

require_once 'config.php';

$names = [];

try {
    $sql = "SELECT * FROM names ORDER BY id";
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

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            margin: 0 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #28a745;
        }

        /* Кнопка добавления в правом нижнем углу */
        .add-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #27ae60;
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
            border: none;
            cursor: pointer;
        }

        .add-button:hover {
            background: #219a52;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
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

            .add-button {
                padding: 12px 24px;
                font-size: 1rem;
                bottom: 20px;
                right: 20px;
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
                            <?php 
                            $firstRow = $names[0];
                            foreach (array_keys($firstRow) as $column): 
                            ?>
                                <th><?php echo htmlspecialchars($column); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($names as $row): ?>
                            <tr>
                                <?php foreach ($row as $value): ?>
                                    <td><?php echo htmlspecialchars($value ?? '—'); ?></td>
                                <?php endforeach; ?>
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
            
        </div>
    </div>

    <!-- Кнопка добавления в правом нижнем углу -->
    


    <a href = "https://vk.ru/video-155939640_456239467?access_key=35e4816f64ec80e260">✓Если вы обнаружили новый тип опасности     <p>Свяжитесь с нами: 392e96alr0t@emailax.pro</p></a>
    <div style="margin-bottom: 20px;">
<a href="add_person.php" class="nav-btn">Добавить персонажа</a>


</div> 
</html>
</body>