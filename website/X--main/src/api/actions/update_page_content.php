<?php
if (isset($_POST['page_content']) && is_array($_POST['page_content'])) {
    foreach ($_POST['page_content'] as $key => $content) {
        $db_key = "page_content_" . $key;
        update_setting($pdo, $db_key, $content);
    }
}
$redirect_url = 'admin.php?view=pages';
?>