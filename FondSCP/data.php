<?php
require_once 'config.php';

$sql = "SELECT * FROM names WHERE id = id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$names = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>