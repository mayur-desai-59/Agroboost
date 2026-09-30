<?php
include 'config.php';

$month = isset($_GET['month']) ? intval($_GET['month']) : 0;
$year = isset($_GET['year']) ? intval($_GET['year']) : 0;

$sql = "SELECT DATE(created_at) as date, COUNT(*) as count FROM agro_products WHERE 1";

if ($month > 0) {
    $sql .= " AND MONTH(created_at) = $month";
}
if ($year > 0) {
    $sql .= " AND YEAR(created_at) = $year";
}

$sql .= " GROUP BY DATE(created_at) ORDER BY DATE(created_at) ASC";

$result = mysqli_query($conn, $sql);

$dates = [];
$counts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $dates[] = $row['date'];
    $counts[] = $row['count'];
}

echo json_encode(['dates' => $dates, 'counts' => $counts]);
?>
