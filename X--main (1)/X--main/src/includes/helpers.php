<?php
function get_all_settings($pdo) {
    $settings = [];
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Try to decode JSON, if it fails, use the raw value
        $value = json_decode($row['setting_value'], true);
        $settings[$row['setting_key']] = (json_last_error() === JSON_ERROR_NONE) ? $value : $row['setting_value'];
    }
    return $settings;
}

function calculate_stats($orders, $days = null) {
    $filtered_orders = $orders;
    if ($days !== null) {
        $cutoff_date = new DateTime();
        if ($days == 0) { $cutoff_date->setTime(0, 0, 0); }
        else { $cutoff_date->modify("-{$days} days"); }
        $filtered_orders = array_filter($orders, function ($order) use ($cutoff_date) {
            $order_date = new DateTime($order['order_date']);
            return $order_date >= $cutoff_date;
        });
    }
    $stats = ['total_revenue' => 0, 'total_orders' => count($filtered_orders), 'pending_orders' => 0, 'confirmed_orders' => 0, 'cancelled_orders' => 0];
    foreach ($filtered_orders as $order) {
        if ($order['status'] === 'Confirmed') { $stats['total_revenue'] += $order['totals']['total']; $stats['confirmed_orders']++; }
        elseif ($order['status'] === 'Pending') { $stats['pending_orders']++; }
        elseif ($order['status'] === 'Cancelled') { $stats['cancelled_orders']++; }
    }
    return $stats;
}

function update_setting($pdo, $key, $value) {
    if (is_array($value) || is_object($value)) {
        $value = json_encode($value, JSON_UNESCAPED_SLASHES);
    }
    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
    $stmt->execute([$key, $value, $value]);
}

function slugify($text) { if (empty($text)) return 'n-a-' . rand(100, 999); $text = preg_replace('~[^\pL\d]+~u', '-', $text); if (function_exists('iconv')) { $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text); } $text = preg_replace('~[^-\w]+~', '', $text); $text = trim($text, '-'); $text = preg_replace('~-+~', '-', $text); $text = strtolower($text); return $text; }
function handle_image_upload($file_input, $upload_dir, $prefix = '') { if (isset($file_input) && $file_input['error'] === UPLOAD_ERR_OK) { $original_filename = basename($file_input['name']); $safe_filename = preg_replace("/[^a-zA-Z0-9-_\.]/", "", $original_filename); $destination = $upload_dir . $prefix . time() . '-' . uniqid() . '-' . $safe_filename; if (move_uploaded_file($file_input['tmp_name'], $destination)) { return $destination; } } return null; }
function send_email($to, $subject, $body, $config) { $mail = new PHPMailer(true); $smtp_settings = $config['smtp_settings'] ?? []; $admin_email = $smtp_settings['admin_email'] ?? ''; $app_password = $smtp_settings['app_password'] ?? ''; if (empty($admin_email) || empty($app_password)) return false; try { $mail->CharSet = 'UTF-8'; $mail->isSMTP(); $mail->Host = 'smtp.gmail.com'; $mail->SMTPAuth = true; $mail->Username = $admin_email; $mail->Password = $app_password; $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; $mail->Port = 465; $mail->setFrom($admin_email, 'Submonth'); $mail->addAddress($to); $mail->isHTML(true); $mail->Subject = $subject; $mail->Body = $body; $mail->send(); return true; } catch (Exception $e) { return false; } }
?>