<?php
update_setting($pdo, 'hero_slider_interval', (int)$_POST['hero_slider_interval'] * 1000);
$current_banners = $site_config['hero_banner'] ?? [];
if (isset($_POST['delete_hero_banners'])) { foreach ($_POST['delete_hero_banners'] as $i => $v) { if ($v === 'true' && isset($current_banners[$i]) && file_exists($current_banners[$i])) { unlink($current_banners[$i]); $current_banners[$i] = null; } } }
for ($i = 0; $i < 10; $i++) { if (isset($_FILES['hero_banners']['tmp_name'][$i]) && is_uploaded_file($_FILES['hero_banners']['tmp_name'][$i])) { if (isset($current_banners[$i]) && file_exists($current_banners[$i])) unlink($current_banners[$i]); $file = ['name' => $_FILES['hero_banners']['name'][$i], 'tmp_name' => $_FILES['hero_banners']['tmp_name'][$i], 'error' => $_FILES['hero_banners']['error'][$i]]; if($dest = handle_image_upload($file, $upload_dir, 'hero-')) $current_banners[$i] = $dest; } }
update_setting($pdo, 'hero_banner', array_values(array_filter($current_banners)));
$redirect_url = 'admin.php?view=settings';
?>