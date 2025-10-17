<?php
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id_unique = ?"); $stmt->execute([$_POST['order_id']]);
$order_to_email = $stmt->fetch();
if ($order_to_email) {
    $item_stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?"); $item_stmt->execute([$order_to_email['id']]);
    $order_items = $item_stmt->fetchAll();
    $email_subject = "Your Submonth Order #" . $order_to_email['order_id_unique'] . " is Confirmed!";
    $access_details = $_POST['access_details'];
    // Email Body Generation
    $email_body = '<p>Dear ' . htmlspecialchars($order_to_email['customer_name']) . ',</p>'; // ... and so on
    if (send_email($_POST['customer_email'], $email_subject, $email_body, $site_config)) {
        $stmt = $pdo->prepare("UPDATE orders SET access_email_sent = 1 WHERE order_id_unique = ?");
        $stmt->execute([$_POST['order_id']]);
    }
}
$redirect_url = 'admin.php?view=orders';
?>