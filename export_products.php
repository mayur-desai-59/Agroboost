<?php
include 'config.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=products_list.csv');

$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, array('Product Name', 'Price', 'Date Added'));

// Optional filters (month/year)
$month = isset($_GET['month']) ? intval($_GET['month']) : 0;
$year = isset($_GET['year']) ? intval($_GET['year']) : 0;

$sql = "SELECT name, price, created_at FROM agro_products WHERE 1";

if ($month > 0) {
    $sql .= " AND MONTH(created_at) = $month";
}
if ($year > 0) {
    $sql .= " AND YEAR(created_at) = $year";
}

$sql .= " ORDER BY created_at ASC";

$result = mysqli_query($conn, $sql);

// Output the data
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, array($row['name'], $row['price'], $row['created_at']));
}

fclose($output);
exit();
?>
