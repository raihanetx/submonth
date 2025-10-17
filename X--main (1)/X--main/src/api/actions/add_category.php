<?php
$name = htmlspecialchars(trim($_POST['name']));
$stmt = $pdo->prepare("INSERT INTO categories (name, slug, icon) VALUES (?, ?, ?)");
$stmt->execute([$name, slugify($name), htmlspecialchars(trim($_POST['icon']))]);
$redirect_url = 'admin.php?view=categories';
?>