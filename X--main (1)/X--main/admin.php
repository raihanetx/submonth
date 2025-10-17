<?php
session_start();
require_once 'src/includes/db.php';
require_once 'src/includes/helpers.php';

// --- Security Check ---
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// --- Load ALL Data from DATABASE ---
$site_config = get_all_settings($pdo);

// Load data required for all views
$all_hotdeals_data = $pdo->query("SELECT h.product_id as productId, h.custom_title as customTitle FROM hotdeals h")->fetchAll(PDO::FETCH_ASSOC);
$all_products_data = [];
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
foreach ($categories as $category) {
    $product_stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? ORDER BY name ASC");
    $product_stmt->execute([$category['id']]);
    $products = $product_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($products as &$product) {
        $pricing_stmt = $pdo->prepare("SELECT * FROM product_pricing WHERE product_id = ?");
        $pricing_stmt->execute([$product['id']]);
        $product['pricing'] = $pricing_stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    unset($product);

    $all_products_data[] = [
        'id' => $category['id'],
        'name' => $category['name'],
        'slug' => $category['slug'],
        'icon' => $category['icon'],
        'products' => $products
    ];
}
$all_coupons_data = $pdo->query("SELECT * FROM coupons ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$all_orders_data_raw = $pdo->query("SELECT *, order_id_unique as order_id FROM orders ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
foreach ($all_orders_data_raw as &$order) {
    $items_stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $items_stmt->execute([$order['id']]);
    $items_result = $items_stmt->fetchAll(PDO::FETCH_ASSOC);

    $order['items'] = [];
    foreach($items_result as $item){
        $order['items'][] = [
            'id' => $item['product_id'], 'name' => $item['product_name'], 'quantity' => $item['quantity'],
            'pricing' => ['duration' => $item['duration'], 'price' => $item['price_at_purchase']]
        ];
    }
    $order['customer'] = ['name' => $order['customer_name'], 'phone' => $order['customer_phone'], 'email' => $order['customer_email']];
    $order['payment'] = ['method' => $order['payment_method'], 'trx_id' => $order['payment_trx_id']];
    $order['coupon'] = ['code' => $order['coupon_code']];
    $order['totals'] = ['subtotal' => (float)$order['subtotal'], 'discount' => (float)$order['discount'], 'total' => (float)$order['total']];
}
unset($order);

$stats_today = calculate_stats($all_orders_data_raw, 0);
$stats_7_days = calculate_stats($all_orders_data_raw, 7);
$stats_30_days = calculate_stats($all_orders_data_raw, 30);
$stats_6_months = calculate_stats($all_orders_data_raw, 180);
$stats_all_time = calculate_stats($all_orders_data_raw);

$pending_orders_count = 0;
foreach($all_orders_data_raw as $o) { if($o['status'] === 'Pending') $pending_orders_count++; }

$all_reviews = $pdo->query("
    SELECT r.*, p.name as product_name 
    FROM product_reviews r 
    JOIN products p ON r.product_id = p.id 
    ORDER BY r.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

$all_products_for_js = $pdo->query("
    SELECT p.id, p.name, c.name as category 
    FROM products p 
    JOIN categories c ON p.category_id = c.id
")->fetchAll(PDO::FETCH_ASSOC);

$current_view = $_GET['view'] ?? 'dashboard';
$category_to_manage = null;
if (isset($_GET['category'])) {
    foreach ($all_products_data as $category) {
        if ($category['name'] === $_GET['category']) {
            $category_to_manage = $category;
            break;
        }
    }
}

include 'src/admin/views/main.php';
?>