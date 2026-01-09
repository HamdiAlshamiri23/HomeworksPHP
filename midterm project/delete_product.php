<?php
require_once 'config/auth.php';
require_once 'config/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
}

redirect('products.php');
?>
