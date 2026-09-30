<?php
include 'config.php';

// Set headers to download file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="customer_registrations.csv"');

$output = fopen('php://output', 'w');

// Write CSV column headings
fputcsv($output, ['User ID', 'Name', 'Email', 'Contact', 'Registration Date']);

// Fetch customer users
$query = "SELECT user_id, name, email, contact, created_at FROM agro_users WHERE role = 'customer' ORDER BY created_at ASC";
$result = mysqli_query($conn, $query);

// Write rows
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

fclose($output);
?>
