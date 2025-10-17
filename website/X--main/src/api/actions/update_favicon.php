<?php
$current_favicon = $site_config['favicon'] ?? '';
if (isset($_POST['delete_favicon']) && !empty($current_favicon) && file_exists($current_favicon)) { unlink($current_favicon); $current_favicon = ''; }
if ($dest = handle_image_upload($_FILES['favicon'] ?? null, $upload_dir, 'favicon-')) { if(!empty($current_favicon) && file_exists($current_favicon)) unlink($current_favicon); $current_favicon = $dest; }
update_setting($pdo, 'favicon', $current_favicon);
$redirect_url = 'admin.php?view=settings';
?>