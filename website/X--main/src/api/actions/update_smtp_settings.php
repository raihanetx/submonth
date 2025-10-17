<?php
$smtp_settings = $site_config['smtp_settings'] ?? [];
if (isset($_POST['admin_email'])) { $smtp_settings['admin_email'] = htmlspecialchars(trim($_POST['admin_email'])); }
if (!empty(trim($_POST['app_password']))) { $smtp_settings['app_password'] = trim($_POST['app_password']); }
update_setting($pdo, 'smtp_settings', $smtp_settings);
$redirect_url = 'admin.php?view=settings';
?>