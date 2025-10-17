<?php
$review_data = $json_data['review'];
$stmt = $pdo->prepare("INSERT INTO product_reviews (product_id, name, rating, comment) VALUES (?, ?, ?, ?)");
$stmt->execute([$review_data['productId'], htmlspecialchars($review_data['name']), (int)$review_data['rating'], htmlspecialchars($review_data['comment'])]);
header('Content-Type: application/json'); echo json_encode(['success' => true]); exit;
?>