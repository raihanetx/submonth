<?php
$current_logo = $site_config['site_logo'] ?? '';
if (isset($_POST['delete_site_logo']) && !empty($current_logo) && file_exists($current_logo)) { unlink($current_logo); $current_logo = ''; }
if ($dest = handle_image_upload($_FILES['site_logo'] ?? null, $upload_dir, 'logo-')) { if(!empty($current_logo) && file_exists($current_logo)) unlink($current_logo); $current_logo = $dest; }
update_setting($pdo, 'site_logo', $current_logo);
$redirect_url = 'admin.php?view=settings';
?>