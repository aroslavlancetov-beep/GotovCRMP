<?php
require_once 'check_auth.php';
// view.php
require_once 'config.php';

// Получаем ID персонажа из URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Получаем данные персонажа с JOIN-ами для связанных таблиц
try {
    $sql = "SELECT 
                n.*,
                d.class_name as danger_name,
                d.description as danger_description,
                s.skill_name,
                s.effect_description,
                s.activation_conditions,
                s.countermeasures
            FROM names n
            LEFT JOIN dangers d ON n.descriptions = d.id
            LEFT JOIN skills s ON n.skill_id = s.id
            WHERE n.id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $character = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Ошибка запроса: ' . $e->getMessage());
    header('Location: index.php');
    exit;
}

if (!$character) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Просмотр персонажа: <?php echo htmlspecialchars($character['object_name']); ?></title>
</head>
<body>
    <h1>персонаж</h1>
    
    <p><a href="index.php">Назад </a></p>
    
    
    
    
    <h2>Основная информация</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <td><?php echo htmlspecialchars($character['id']); ?></td>
        </tr>
        <tr>
            <th>Имя персонажа</th>
            <td><?php echo htmlspecialchars($character['object_name']); ?></td>
        </tr>
        <tr>
            <th>Псевдоним / Группировка</th>
            <td><?php echo htmlspecialchars($character['alias'] ?? '—'); ?></td>
        </tr>
        <tr>
            <th>Дата обнаружения</th>
            <td><?php echo htmlspecialchars($character['discovery_date'] ?? '—'); ?></td>
        </tr>
    </table>
    
    <h2>Уровень опасности</h2>
    <?php if ($character['danger_name']): ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>Класс опасности</th>
                <td><?php echo htmlspecialchars($character['danger_name']); ?></td>
            </tr>
            <tr>
                <th>Описание</th>
                <td><?php echo nl2br(htmlspecialchars($character['danger_description'] ?? '—')); ?></td>
            </tr>
        </table>
    <?php else: ?>
        <p>Уровень опасности не указан</p>
    <?php endif; ?>
    
    <h2>Навык / Способность</h2>
    <?php if ($character['skill_name']): ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>Название навыка</th>
                <td><?php echo htmlspecialchars($character['skill_name']); ?></td>
            </tr>
            <tr>
                <th>Эффект</th>
                <td><?php echo nl2br(htmlspecialchars($character['effect_description'] ?? '—')); ?></td>
            </tr>
            <tr>
                <th>Условия активации</th>
                <td><?php echo nl2br(htmlspecialchars($character['activation_conditions'] ?? '—')); ?></td>
            </tr>
            <tr>
                <th>Способы противодействия</th>
                <td><?php echo nl2br(htmlspecialchars($character['countermeasures'] ?? '—')); ?></td>
            </tr>
        </table>
    <?php else: ?>
        <p>Навык не указан</p>
    <?php endif; ?>
    
    
    
</body>
</html>