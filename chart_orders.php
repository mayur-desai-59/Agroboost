<?php
include 'config.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: main.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Total Orders - Sai Agro Fertilizers</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background-color: #ffffff;
    }

    .dashboard-container {
      display: flex;
    }

    .main-panel {
      margin-left: 240px;
      width: calc(100% - 240px);
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
    }

    .filters {
      margin-bottom: 20px;
      display: flex;
      gap: 10px;
      align-items: center;
    }

    select, button {
      padding: 5px 10px;
      font-size: 16px;
    }

    .export-btn {
      margin-left: auto;
      background-color: #2e7d32;
      color: white;
      padding: 7px 14px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }

    .export-btn:hover {
      background-color: #1b5e20;
    }

    .chart-container {
      background-color: #fff;
      padding: 20px;
      border: 2px solid #2e7d32;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }
  </style>
</head>
<body>

<div class="dashboard-container">
  
  <?php include 'admin_sidebar.php'; ?>

  <main class="main-panel">
    
    <?php include 'admin_header.php'; ?>

    <div class="main">
      <h2>🛒 Orders Overview</h2>

      <div class="filters">
        <form method="GET">
          <select name="month">
            <option value="">All Months</option>
            <?php
            for ($m = 1; $m <= 12; $m++) {
              $monthName = date('F', mktime(0, 0, 0, $m, 1));
              echo "<option value='$m'>" . $monthName . "</option>";
            }
            ?>
          </select>

          <select name="year">
            <option value="">All Years</option>
            <?php
            $currentYear = date('Y');
            for ($y = 2020; $y <= $currentYear; $y++) {
              echo "<option value='$y'>$y</option>";
            }
            ?>
          </select>

          <button type="submit">Filter</button>
        </form>

        <button class="export-btn" onclick="exportOrders()">Export</button>
      </div>

      <div class="chart-container">
        <canvas id="ordersChart"></canvas>
      </div>
    </div>

  </main>

</div>

<script>
function exportOrders() {
  window.location.href = 'export_orders.php';
}

fetch('fetch_orders_chart_data.php<?php echo (isset($_GET['month']) || isset($_GET['year'])) ? '?month=' . $_GET['month'] . '&year=' . $_GET['year'] : ''; ?>')
  .then(response => response.json())
  .then(data => {
    const ctx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.dates,
        datasets: [{
          label: 'Orders',
          data: data.counts,
          backgroundColor: '#66bb6a'
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            precision: 0
          }
        }
      }
    });
  });
</script>

</body>
</html>
