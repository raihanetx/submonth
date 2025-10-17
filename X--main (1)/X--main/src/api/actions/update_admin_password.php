<?php
if (!empty(trim($_POST['new_password']))) {
    $new_password_hashed = password_hash(trim($_POST['new_password']), PASSWORD_DEFAULT);
    update_setting($pdo, 'admin_password', $new_password_hashed);
}
$redirect_url = 'admin.php?view=settings';
?>