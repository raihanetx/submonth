<?php
update_setting($pdo, 'usd_to_bdt_rate', (float)$_POST['usd_to_bdt_rate']);
$redirect_url = 'admin.php?view=settings';
?>