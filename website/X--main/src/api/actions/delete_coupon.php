<?php
$stmt = $pdo->prepare("DELETE FROM coupons WHERE id = ?");
$stmt->execute([$_POST['coupon_id']]);
$redirect_url = 'admin.php?view=dashboard';
?>