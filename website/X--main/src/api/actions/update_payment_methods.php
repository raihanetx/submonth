<?php
$payment_methods = $site_config['payment_methods'] ?? [];
foreach ($_POST['payment_methods'] as $name => $details) {
    if (isset($details['number'])) $payment_methods[$name]['number'] = htmlspecialchars(trim($details['number']));
    if (isset($details['pay_id'])) $payment_methods[$name]['pay_id'] = htmlspecialchars(trim($details['pay_id']));
    if (isset($_POST['delete_logos'][$name]) && !empty($payment_methods[$name]['logo_url']) && file_exists($payment_methods[$name]['logo_url'])) { unlink($payment_methods[$name]['logo_url']); $payment_methods[$name]['logo_url'] = ''; }
    if (isset($_FILES['payment_logos']['name'][$name]) && $_FILES['payment_logos']['error'][$name] === UPLOAD_ERR_OK) {
        $file = ['name' => $_FILES['payment_logos']['name'][$name], 'tmp_name' => $_FILES['payment_logos']['tmp_name'][$name], 'error' => $_FILES['payment_logos']['error'][$name]];
        if($dest = handle_image_upload($file, $upload_dir, 'payment-')) { if(!empty($payment_methods[$name]['logo_url']) && file_exists($payment_methods[$name]['logo_url'])) unlink($payment_methods[$name]['logo_url']); $payment_methods[$name]['logo_url'] = $dest; }
    }
}
update_setting($pdo, 'payment_methods', $payment_methods);
$redirect_url = 'admin.php?view=settings';
?>