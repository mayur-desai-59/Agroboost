<?php
session_start();
include 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT o.order_id, p.name, p.image_url, p.price, o.quantity, o.ordered_at
        FROM agro_orders o
        JOIN agro_products p ON o.product_id = p.product_id
        WHERE o.user_id = ?
        ORDER BY o.order_id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: green;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
        }
        th {
            background-color: #28a745;
            color: white;
        }
        img {
            width: 60px;
            height: auto;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<h2>My Orders</h2>

<table>
    <tr>
        <th>Order ID</th>
        <th>Product Image</th>
        <th>Product Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Order Date</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($order = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $order['order_id'] . "</td>";
            echo "<td><img src='" . $order['image_url'] . "' alt='Product Image'></td>";
            echo "<td>" . $order['name'] . "</td>";
            echo "<td>₹" . $order['price'] . "</td>";
            echo "<td>" . $order['quantity'] . "</td>";
            echo "<td>" . $order['ordered_at'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No orders found</td></tr>";
    }
    ?>
</table>

</body>
</html>

<?php $stmt->close(); $conn->close(); ?>
