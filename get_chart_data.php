<?php
require_once 'config.php'; // your DB connection file

// Users data
$userQuery = "SELECT DATE(created_at) as date, COUNT(*) as count FROM agro_users GROUP BY DATE(created_at)";
$userResult = mysqli_query($conn, $userQuery);

$userLabels = [];
$userCounts = [];

while ($row = mysqli_fetch_assoc($userResult)) {
    $userLabels[] = $row['date'];
    $userCounts[] = $row['count'];
}

// Purchases data
$purchaseQuery = "SELECT DATE(purchase_date) as date, COUNT(*) as count FROM agro_purchases GROUP BY DATE(purchase_date)";
$purchaseResult = mysqli_query($conn, $purchaseQuery);

$purchaseLabels = [];
$purchaseCounts = [];

while ($row = mysqli_fetch_assoc($purchaseResult)) {
    $purchaseLabels[] = $row['date'];
    $purchaseCounts[] = $row['count'];
}

// Send JSON
echo json_encode([
    "userLabels" => $userLabels,
    "userCounts" => $userCounts,
    "purchaseLabels" => $purchaseLabels,
    "purchaseCounts" => $purchaseCounts
]);
?>
<?php



?>