<?php
// chart_products.php
session_start();
include('admin_header.php'); // your existing header file
include('admin_sidebar.php'); // your existing sidebar file
include('db_connect.php'); // your db connection

// Fetch data
$where = "";
if (isset($_GET['month']) && $_GET['month'] != 'All') {
    $month = $_GET['month'];
    $where .= " AND MONTH(added_at) = $month";
}
if (isset($_GET['year']) && $_GET['year'] != 'All') {
    $year = $_GET['year'];
    $where .= " AND YEAR(added_at) = $year";
}

$query = "SELECT DATE(added_at) as add_date, COUNT(*) as total FROM agro_products WHERE 1 $where GROUP BY DATE(added_at) ORDER BY add_date ASC";
$result = mysqli_query($conn, $query);

$data = [];
while($row = mysqli_fetch_assoc($result)){
    $data[] = $row;
}
?>

<div class="main-content">
    <h2>📦 Product Additions Over Time</h2>
    <form method="GET" class="filter-form">
        <label>Month:</label>
        <select name="month">
            <option value="All">All</option>
            <?php for ($m=1; $m<=12; $m++): ?>
                <option value="<?= $m; ?>" <?= (isset($_GET['month']) && $_GET['month']==$m) ? 'selected' : ''; ?>><?= $m; ?></option>
            <?php endfor; ?>
        </select>

        <label>Year:</label>
        <select name="year">
            <option value="All">All</option>
            <?php
            $yearNow = date('Y');
            for ($y=$yearNow; $y>=2020; $y--): ?>
                <option value="<?= $y; ?>" <?= (isset($_GET['year']) && $_GET['year']==$y) ? 'selected' : ''; ?>><?= $y; ?></option>
            <?php endfor; ?>
        </select>

        <button type="submit">Filter</button>
        <a href="export_products.php" class="export-button">Export Data</a>
    </form>

    <canvas id="productsChart" style="width:100%; height:400px;"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function loadChartData(month = 'All', year = 'All') {
    fetch(`fetch_products_chart_data.php?month=${month}&year=${year}`)
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.add_date);
            const values = data.map(item => item.total);

            if (window.productsChart) {
                productsChart.data.labels = labels;
                productsChart.data.datasets[0].data = values;
                productsChart.update();
            } else {
                const ctx = document.getElementById('productsChart').getContext('2d');
                window.productsChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Products Added',
                            data: values,
                            backgroundColor: '#4CAF50'
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        });
}

document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    const month = this.month.value;
    const year = this.year.value;
    loadChartData(month, year);
});

// Initial load
loadChartData();
</script>


<?php include('admin_footer.php'); ?>
