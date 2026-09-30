<?php
session_start();
include 'config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: main.html");
    exit();
}

$result = mysqli_query($conn, "SELECT user_id, name, email, role, contact FROM agro_users WHERE role = 'customer'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Customers - Sai Agro Fertilizers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            display: flex;
        }

        .main-panel {
            flex-grow: 1;
            background-color: #ffffff;
            min-height: 100vh;
        }

        .main {
            padding: 40px 20px;
        }

        .main h2 {
            color: #2e7d32;
            margin-bottom: 30px;
            font-size: 28px;
            text-align: center;
            margin-top: 100px; /* Heading pushed down */
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


        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #2e7d32;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="dashboard-container">

    <?php include 'admin_sidebar.php'; ?>

    <main class="main-panel">
        
        <?php include 'admin_header.php'; ?>

        <div class="main">
            <h2>      📋 Registered Customers</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Contact No</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['user_id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= ucfirst(htmlspecialchars($row['role'])) ?></td>
                            <td><?= htmlspecialchars($row['contact']) ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>

    </main>

</div>

</body>
</html>
