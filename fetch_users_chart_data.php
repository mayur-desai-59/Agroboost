<?php
include 'config.php';

$month = isset($_GET['month']) ? intval($_GET['month']) : 0;
$year = isset($_GET['year']) ? intval($_GET['year']) : 0;

// Build SQL Query
$query = "SELECT DATE(created_at) AS reg_date, COUNT(*) AS total FROM agro_users 
          WHERE role = 'customer'";

if ($month > 0) {
    $query .= " AND MONTH(created_at) = $month";
}
if ($year > 0) {
    $query .= " AND YEAR(created_at) = $year";
}

$query .= " GROUP BY DATE(created_at) ORDER BY reg_date ASC";

$result = mysqli_query($conn, $query);

$dates = [];
$counts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $dates[] = $row['reg_date'];
    $counts[] = $row['total'];
}

echo json_encode(['dates' => $dates, 'counts' => $counts]);
?>
