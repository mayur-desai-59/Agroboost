<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'config.php';

$result = mysqli_query($conn, "SELECT * FROM agro_products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Products - Sai Agro Fertilizers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .main {
            padding: 40px;
            background-color: #ffffff;
            min-height: 100vh;
        }

        .main h2 {
            color: #2e7d32;
            margin-bottom: 30px;
            font-size: 28px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #2e7d32;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .edit-btn {
            background-color: #ffc107;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
.table-container {
        background-color: #fff;
        padding: 20px;
        border: 2px solid #2e7d32;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        width: 70%;
        margin: 30px 0 0 250px; /* <-- move table slightly left */
    }


    </style>
</head>
<body>

<div class="dashboard-container">

    <?php include 'admin_sidebar.php'; ?>

    <main class="main-panel">

        <?php include 'admin_header.php'; ?>

        <div class="main">
            <h2 style="margin-top: 80px;">🛒 View Products</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Price (₹)</th>
                            <th>KG</th>
                            <th>Stock Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['product_id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['price']) ?></td>
                            <td><?= htmlspecialchars($row['kg'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($row['stock_status']) ?></td>
                            <td>
                                <a href="edit_product.php?id=<?= $row['product_id'] ?>" class="btn edit-btn">Edit</a>
                                <a href="delete_product.php?id=<?= $row['product_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <a href="add_product.php" class="back-btn"><i class="fas fa-plus"></i> Add New Product</a>
            </div>

        </div>

    </main>

</div>

</body>
</html>
