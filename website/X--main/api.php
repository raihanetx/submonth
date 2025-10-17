<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

session_start();
require_once 'src/includes/db.php';
require_once 'src/includes/helpers.php';

$upload_dir = 'uploads/';
if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }

$site_config = get_all_settings($pdo);

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$json_data = null;

if (!$action) {
    $json_data = json_decode(file_get_contents('php://input'), true);
    $action = $json_data['action'] ?? null;
}

if (!$action) {
    http_response_code(400);
    die("Action not specified.");
}

$admin_actions = [
    'add_category', 'delete_category', 'edit_category', 'add_product', 'delete_product',
    'edit_product', 'add_coupon', 'delete_coupon', 'update_review_status',
    'update_order_status', 'update_hero_banner', 'update_favicon', 'update_currency_rate',
    'update_contact_info', 'update_admin_password', 'update_site_logo', 'update_hot_deals',
    'update_payment_methods', 'update_smtp_settings', 'send_manual_email', 'update_page_content'
];

if (in_array($action, $admin_actions)) {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        http_response_code(403);
        die("Forbidden: You must be logged in.");
    }
}

$action_file = "src/api/actions/{$action}.php";

if (file_exists($action_file)) {
    require $action_file;
} else {
    http_response_code(404);
    die("Action not found.");
}

if (isset($redirect_url)) {
    header('Location: ' . $redirect_url);
    exit;
}
?>