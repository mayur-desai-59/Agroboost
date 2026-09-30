<?php
include 'config.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: main.html");
    exit();
}

$userCount = $conn->query("SELECT COUNT(*) as total FROM agro_users")->fetch_assoc()['total'];
$productCount = $conn->query("SELECT COUNT(*) as total FROM agro_products")->fetch_assoc()['total'];
$orderCount = $conn->query("SELECT COUNT(*) as total FROM agro_orders")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Sai Agro Fertilizers</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #ffffff;
        }

    .main-panel {
      margin-left: 240px;
      background-color: #ffffff;
    }

    .main {
      padding: 100px 40px 40px; /* 🟢 Add padding-top for space below header */
      min-height: 100vh;
    }

    .main h2 {
      color: #2e7d32;
      margin-bottom: 30px;
      font-size: 28px;
    }

    .cards-container {
      display: flex;
      gap: 70px;
      flex-wrap: wrap;
      margin-top: 20px;
    }

    .card {
      background: #ffffff;
      border: 2px solid #2e7d32;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      width: 250px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
      transition: 0.3s;
      text-decoration: none;
      color: #2e7d32;
      font-weight: bold;
    }

    .card:hover {
      background-color: #e8f5e9;
      transform: translateY(-5px);
    }

    .card strong {
      display: block;
      font-size: 25px;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<div class="dashboard-container">

  <?php include 'admin_sidebar.php'; ?>

  <main class="main-panel">

    <?php include 'admin_header.php'; ?>

    <div class="main">
      <h2>🌱 Welcome back, Admin!</h2>

      <div class="cards-container">
		<a href="chart_users.php" class="card">
          👤 <div>Total Users</div>
          <strong><?php echo $userCount; ?></strong>
        </a>

<a href="chart_products.php" class="card">
          🌾 <div>Total Products</div>
          <strong><?php echo $productCount; ?></strong>
        </a>

<a href="chart_orders.php" class="card">
          🛒 <div>Total Orders</div>
          <strong><?php echo $orderCount; ?></strong>
        </a>
      </div>
    </div>

  </main>
</div>

</body>
</html>
