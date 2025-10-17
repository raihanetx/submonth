<?php
$stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
$stmt->execute([$_POST['category_name']]);
$category_id = $stmt->fetchColumn();
if ($category_id) {
    $name = htmlspecialchars(trim($_POST['name']));
    $image_path = handle_image_upload($_FILES['image'] ?? null, $upload_dir, 'product-');
    $stmt = $pdo->prepare("INSERT INTO products (category_id, name, slug, description, long_description, image, stock_out, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$category_id, $name, slugify($name), htmlspecialchars(trim($_POST['description'])), $_POST['long_description'] ?? null, $image_path, ($_POST['stock_out'] ?? 'false') === 'true', isset($_POST['featured'])]);
    $product_id = $pdo->lastInsertId();
    if (!empty($_POST['durations'])) {
        foreach ($_POST['durations'] as $key => $duration) {
            $stmt = $pdo->prepare("INSERT INTO product_pricing (product_id, duration, price) VALUES (?, ?, ?)");
            $stmt->execute([$product_id, htmlspecialchars(trim($duration)), (float)$_POST['duration_prices'][$key]]);
        }
    } else {
        $stmt = $pdo->prepare("INSERT INTO product_pricing (product_id, duration, price) VALUES (?, ?, ?)");
        $stmt->execute([$product_id, 'Default', (float)$_POST['price']]);
    }
}
$redirect_url = 'admin.php?category=' . urlencode($_POST['category_name']);
?>