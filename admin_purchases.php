<?php
include 'config.php'; // Database connection

// Fetch purchases data
$sql = "SELECT * FROM agro_orders ORDER BY order_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Records</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }

        .container {
            margin-left: 280px; /* Push right to accommodate sidebar */
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
            color: green;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #28a745;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <?php include 'admin_header.php'; ?>
    <?php include 'admin_sidebar.php'; ?>

    <div class="container">
        <h1 style="margin-top: 80px;">Purchase Records</h1>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['order_id'] . "</td>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . $row['product_id'] . "</td>";
                        echo "<td>" . $row['quantity'] . "</td>";
                        echo "<td>₹" . $row['price'] . "</td>";
                        echo "<td>" . $row['ordered_at'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a href="admin_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>

</body>
</html>

<?php $conn->close(); ?>
