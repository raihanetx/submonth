<?php
$order_data = $json_data['order']; $pdo->beginTransaction();
try {
    $order_id_unique = time();
    $subtotal = 0; foreach($order_data['items'] as $item) { $subtotal += $item['pricing']['price'] * $item['quantity']; }
    $discount = $order_data['totals']['discount'] ?? 0;
    $total = $order_data['totals']['total'] ?? $subtotal - $discount;
    $stmt = $pdo->prepare("INSERT INTO orders (order_id_unique, customer_name, customer_phone, customer_email, payment_method, payment_trx_id, coupon_code, subtotal, discount, total, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$order_id_unique, $order_data['customerInfo']['name'], $order_data['customerInfo']['phone'], $order_data['customerInfo']['email'], $order_data['paymentInfo']['method'], $order_data['paymentInfo']['trx_id'], $order_data['coupon']['code'] ?? null, $subtotal, $discount, $total, 'Pending']);
    $order_db_id = $pdo->lastInsertId();
    foreach ($order_data['items'] as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, duration, price_at_purchase) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$order_db_id, $item['id'], $item['name'], $item['quantity'], $item['pricing']['duration'], $item['pricing']['price']]);
    }
    $pdo->commit();
    send_email($site_config['smtp_settings']['admin_email'] ?? '', "New Order #$order_id_unique", "A new order has been placed. Please check the admin panel.", $site_config);
    header('Content-Type: application/json'); echo json_encode(['success' => true, 'order_id' => $order_id_unique]);
} catch (Exception $e) {
    $pdo->rollBack(); header('Content-Type: application/json', true, 500); echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
exit;
?>