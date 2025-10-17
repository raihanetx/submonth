<?php
$stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE order_id_unique = ?");
$stmt->execute([$_POST['new_status'], $_POST['order_id']]);
$redirect_url = 'admin.php?view=orders';
?>