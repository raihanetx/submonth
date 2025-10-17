<?php
$order_ids_to_find = json_decode($_GET['ids'], true);
if (is_array($order_ids_to_find) && !empty($order_ids_to_find)) {
    $placeholders = implode(',', array_fill(0, count($order_ids_to_find), '?'));
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id_unique IN ($placeholders) ORDER BY id DESC");
    $stmt->execute($order_ids_to_find);
    $found_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $orders_with_items = [];
    foreach ($found_orders as $order) {
        $item_stmt = $pdo->prepare("SELECT product_name as name, quantity, duration, price_at_purchase as price, product_id as id FROM order_items WHERE order_id = ?");
        $item_stmt->execute([$order['id']]);
        $items = $item_stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as &$item) { $item['pricing'] = ['duration' => $item['duration'], 'price' => (float)$item['price']]; }
        unset($item);
        $order['items'] = $items;
        $order['order_id'] = $order['order_id_unique'];
        $order['customer'] = ['name' => $order['customer_name'], 'phone' => $order['customer_phone'], 'email' => $order['customer_email']];
        $order['payment'] = ['method' => $order['payment_method'], 'trx_id' => $order['payment_trx_id']];
        $order['coupon'] = ['code' => $order['coupon_code']];
        $order['totals'] = ['subtotal' => (float)$order['subtotal'], 'discount' => (float)$order['discount'], 'total' => (float)$order['total']];
        $orders_with_items[] = $order;
    }
    header('Content-Type: application/json');
    echo json_encode($orders_with_items);
} else {
    header('Content-Type: application/json'); echo json_encode([]);
}
exit;
?>