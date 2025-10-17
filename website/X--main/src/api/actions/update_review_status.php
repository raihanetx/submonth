<?php
if ($_POST['new_status'] === 'deleted') {
    $stmt = $pdo->prepare("DELETE FROM product_reviews WHERE id = ?");
    $stmt->execute([$_POST['review_id']]);
}
$redirect_url = 'admin.php?view=reviews';
?>