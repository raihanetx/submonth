<?php
update_setting($pdo, 'hot_deals_speed', (int)$_POST['hot_deals_speed']);
$pdo->query("DELETE FROM hotdeals");
if (!empty($_POST['selected_deals'])) {
    $stmt = $pdo->prepare("INSERT INTO hotdeals (product_id, custom_title) VALUES (?, ?)");
    foreach($_POST['selected_deals'] as $productId) {
        $stmt->execute([$productId, htmlspecialchars(trim($_POST['custom_titles'][$productId] ?? ''))]);
    }
}
$redirect_url = 'admin.php?view=hotdeals';
?>