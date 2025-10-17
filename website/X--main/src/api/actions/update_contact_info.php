<?php
$contact_info = ['phone' => htmlspecialchars(trim($_POST['phone_number'])), 'whatsapp' => htmlspecialchars(trim($_POST['whatsapp_number'])), 'email' => htmlspecialchars(trim($_POST['email_address']))];
update_setting($pdo, 'contact_info', $contact_info);
$redirect_url = 'admin.php?view=settings';
?>