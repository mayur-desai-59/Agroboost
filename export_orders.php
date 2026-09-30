<?php
include 'config.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: main.html");
    exit();
}

// Set headers to download file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="orders_export.csv"');

$output = fopen('php://output', 'w');

// Column headers
fputcsv($output, ['Order ID', 'User ID', 'Product ID', 'Product Name', 'Quantity', 'Total Price', 'Order Date']);

// Fetch orders from DB
$query = "SELECT order_id, user_id, product_id, product_name, quantity, price, ordered_at FROM agro_orders ORDER BY ordered_at DESC";
$result = mysqli_query($conn, $query);

// Output rows
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>
