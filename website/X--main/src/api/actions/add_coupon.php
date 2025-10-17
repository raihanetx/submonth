<?php
$scope = $_POST['scope'] ?? 'all_products'; $scope_value = null;
if ($scope === 'category') $scope_value = $_POST['scope_value_category'] ?? null; elseif ($scope === 'single_product') $scope_value = $_POST['scope_value_product'] ?? null;
$stmt = $pdo->prepare("INSERT INTO coupons (code, discount_percentage, is_active, scope, scope_value) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([strtoupper(htmlspecialchars(trim($_POST['code']))), (int)$_POST['discount_percentage'], isset($_POST['is_active']), $scope, $scope_value]);
$redirect_url = 'admin.php?view=dashboard';
?>