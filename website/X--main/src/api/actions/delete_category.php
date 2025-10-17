<?php
$stmt = $pdo->prepare("DELETE FROM categories WHERE name = ?");
$stmt->execute([$_POST['name']]);
$redirect_url = 'admin.php?view=categories';
?>