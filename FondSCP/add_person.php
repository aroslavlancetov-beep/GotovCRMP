<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $object_name = trim($_POST['object_name'] ?? '');
    $alias = trim($_POST['alias'] ?? '');
    $discovery_date = trim($_POST['discovery_date'] ?? '');
    $descriptions = trim($_POST['description'] ?? '');
    
    if (empty($object_name)) {
        $error = "Имя объекта обязательно";
    } elseif (empty($alias)) {
        $error = "Псевдоним обязателен";
    } elseif (empty($discovery_date)) {
        $error = "Дата обнаружения обязательна";
    } else {
        try {
            $sql = "INSERT INTO names (object_name, alias, discovery_date, descriptions) 
                    VALUES (:object_name, :alias, :discovery_date, :descriptions)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':object_name' => $object_name,
                ':alias' => $alias,
                ':discovery_date' => $discovery_date,
                ':descriptions' => $descriptions
            ]);
            // $success = "Персонаж успешно добавлен";
            
            $object_name = $alias = $discovery_date = $description = '';
            
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $error = "Персонаж с таким именем уже существует";
            } else {
                $error = "Ошибка базы данных: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title></title>
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
        }

        .container {
            max-width: 600px;
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
            font-size: 2rem;
            margin-bottom: 10px;
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
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-btn:hover {
            background: rgba(255, 255, 255, 0.35);
        }

        .form-container {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            font-family: inherit;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .btn-submit {
            background: #27ae60;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
        }

        .btn-submit:hover {
            background: #219a52;
        }

        .btn-back {
            display: inline-block;
            background: #95a5a6;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            margin-top: 15px;
            text-align: center;
        }

        .btn-back:hover {
            background: #7f8c8d;
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #95a5a6;
            border-top: 1px solid #ecf0f1;
        }

        .hint {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="nav-buttons">
                <a href="index.php" class="nav-btn">НАЗАД</a>
            </div>
            
            
        </div>

        <div class="form-container">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="object_name">Имя Персонажа</label>
                    <input type="text" id="object_name" name="object_name" 
                        value="<?php echo htmlspecialchars($object_name ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="alias">Группировка</label>
                    <input type="text" id="alias" name="alias" 
                        value="<?php echo htmlspecialchars($alias ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="discovery_date">Дата</label>
                    <input type="text" id="discovery_date" name="discovery_date" 
                        value="<?php echo htmlspecialchars($discovery_date ?? ''); ?>" 
                        placeholder="например: 2024-12-25"
                        required>
                    <!-- <div class="hint">Формат: ГГГГ-ММ-ДД (например: 2024-12-25)</div> -->
                </div>

                <div class="form-group">
                    <label for="description">Уровень опасности</label>
                    <textarea id="description" name="descriptions"><?php echo htmlspecialchars($descriptions ?? ''); ?></textarea>
                </div>

                <button class="custom-btn btn-16">ДОБАВИТЬ</button>
            </form>

            
        </div>

        <div class="footer">
            
        </div>
    </div>
</body>
</html>