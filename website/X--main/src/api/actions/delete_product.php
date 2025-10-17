<?php
$stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?"); $stmt->execute([$_POST['product_id']]);
if ($image = $stmt->fetchColumn()) { if (file_exists($image)) unlink($image); }
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?"); $stmt->execute([$_POST['product_id']]);
$redirect_url = 'admin.php?category=' . urlencode($_POST['category_name']);
?>