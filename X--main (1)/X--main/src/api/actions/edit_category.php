<?php
$newName = htmlspecialchars(trim($_POST['name']));
$oldName = $_POST['original_name'];
$stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ?, icon = ? WHERE name = ?");
$stmt->execute([$newName, slugify($newName), htmlspecialchars(trim($_POST['icon'])), $oldName]);
$stmt = $pdo->prepare("UPDATE coupons SET scope_value = ? WHERE scope = 'category' AND scope_value = ?");
$stmt->execute([$newName, $oldName]);
$redirect_url = 'admin.php?view=categories';
?>