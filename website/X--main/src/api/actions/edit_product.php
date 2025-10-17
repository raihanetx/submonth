<?php
$product_id = $_POST['product_id']; $name = htmlspecialchars(trim($_POST['name']));
$stmt = $pdo->prepare("UPDATE products SET name=?, slug=?, description=?, long_description=?, stock_out=?, featured=? WHERE id=?");
$stmt->execute([$name, slugify($name), htmlspecialchars(trim($_POST['description'])), $_POST['long_description'] ?? null, $_POST['stock_out'] === 'true', isset($_POST['featured']), $product_id]);
$stmt = $pdo->prepare("DELETE FROM product_pricing WHERE product_id = ?"); $stmt->execute([$product_id]);
if (!empty($_POST['durations'])) {
    foreach ($_POST['durations'] as $key => $duration) {
        if(!empty(trim($duration))) {
            $stmt = $pdo->prepare("INSERT INTO product_pricing (product_id, duration, price) VALUES (?, ?, ?)");
            $stmt->execute([$product_id, htmlspecialchars(trim($duration)), (float)$_POST['duration_prices'][$key]]);
        }
    }
} else {
    $stmt = $pdo->prepare("INSERT INTO product_pricing (product_id, duration, price) VALUES (?, ?, ?)");
    $stmt->execute([$product_id, 'Default', (float)$_POST['price']]);
}
$stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?"); $stmt->execute([$product_id]); $current_image = $stmt->fetchColumn();
if (isset($_POST['delete_image']) && $current_image && file_exists($current_image)) { unlink($current_image); $stmt = $pdo->prepare("UPDATE products SET image = NULL WHERE id = ?"); $stmt->execute([$product_id]); }
$new_image = handle_image_upload($_FILES['image'] ?? null, $upload_dir, 'product-');
if ($new_image) { if ($current_image && file_exists($current_image)) { unlink($current_image); } $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?"); $stmt->execute([$new_image, $product_id]); }
$redirect_url = 'admin.php?category=' . urlencode($_POST['category_name']);
?>