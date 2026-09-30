<?php
include 'config.php';

$month = isset($_GET['month']) ? $_GET['month'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';

// Build dynamic WHERE clause
$where = "WHERE 1";
if (!empty($month)) {
    $where .= " AND MONTH(ordered_at) = " . intval($month);
}
if (!empty($year)) {
    $where .= " AND YEAR(ordered_at) = " . intval($year);
}

// Fetch order counts grouped by date
$query = "SELECT DATE(ordered_at) as date, COUNT(*) as count FROM agro_orders $where GROUP BY DATE(ordered_at) ORDER BY DATE(ordered_at)";
$result = mysqli_query($conn, $query);

$dates = [];
$counts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $dates[] = $row['date'];
    $counts[] = $row['count'];
}

echo json_encode([
    'dates' => $dates,
    'counts' => $counts
]);
?>
