<?php
require_once 'config.php';

// Получаем ID персонажа из URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Обработка сохранения
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $object_name = $_POST['object_name'];
    $alias = $_POST['alias'];
    $discovery_date = $_POST['discovery_date'];
    $descriptions = $_POST['descriptions'];
    $skill_id = $_POST['skill_id'] ?: null;
    
    $stmt = $pdo->prepare("UPDATE names SET 
        object_name = ?, 
        alias = ?, 
        discovery_date = ?, 
        descriptions = ?, 
        skill_id = ? 
        WHERE id = ?");
    $stmt->execute([$object_name, $alias, $discovery_date, $descriptions, $skill_id, $id]);
    
    header('Location: index.php');
    exit;
}

// Получаем данные персонажа
$stmt = $pdo->prepare("SELECT * FROM names WHERE id = ?");
$stmt->execute([$id]);
$character = $stmt->fetch();

if (!$character) {
    header('Location: index.php');
    exit;
}

// Получаем списки для выпадающих меню
$dangers = $pdo->query("SELECT id, class_name FROM dangers ORDER BY id")->fetchAll();
$skills = $pdo->query("SELECT id, skill_name FROM skills ORDER BY id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование персонажа</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            padding: 20px;
            min-height: 100vh;
            background-image: url('https://cs1.gtavicecity.ru/screenshots/9a0d4/2021-07/original/e71b6fb3e3b577f2ed72dbafc7414c9c4a47527c/935873-gallery11.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        .container {
            max-width: 800px;
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
        }

        .header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .header p {
            opacity: 0.9;
        }

        .form-container {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #cab27d;
        }

        .form-group input {
            background: white;
        }

        .buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-save {
            background: #27ae60;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-save:hover {
            background: #219a52;
        }

        .btn-cancel {
            background: #95a5a6;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-cancel:hover {
            background: #7f8c8d;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #95a5a6;
            font-size: 0.9rem;
            border-top: 1px solid #ecf0f1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Редактирование персонажа</h1>

        </div>

        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label>ID персонажа</label>
                    <input type="text" value="<?php echo $character['id']; ?>" disabled>
                </div>

                <div class="form-group">
                    <label>Имя персонажа *</label>
                    <input type="text" name="object_name" value="<?php echo htmlspecialchars($character['object_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Псевдоним</label>
                    <input type="text" name="alias" value="<?php echo htmlspecialchars($character['alias'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Дата обнаружения</label>
                    <input type="date" name="discovery_date" value="<?php echo $character['discovery_date'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label>Уровень опасности</label>
                    <select name="descriptions">
                        <option value="">-- Выберите --</option>
                        <?php foreach ($dangers as $danger): ?>
                            <option value="<?php echo $danger['id']; ?>" 
                                <?php echo ($character['descriptions'] == $danger['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($danger['class_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Навык</label>
                    <select name="skill_id">
                        <option value="">-- Выберите --</option>
                        <?php foreach ($skills as $skill): ?>
                            <option value="<?php echo $skill['id']; ?>" 
                                <?php echo ($character['skill_id'] == $skill['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($skill['skill_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="buttons">
                    <button type="submit" name="save" class="nav-btn">СОХРАНИТЬ</button>
                    
                </div>
            </form>
</body>
</html>