<!-- admin_sidebar.php -->
<style>
.sidebar {
    width: 200px;
    background-color: #28a745;
    height: 100vh;
    position: fixed;
    color: white;
    padding-top: 30px;
    top: 0;
    left: 0;
}
.sidebar a {
    display: block;
    padding: 15px;
    color: white;
    text-decoration: none;
    font-weight: bold;
}
.sidebar a:hover {
    background-color: #218838;
}
.sidebar h3 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 22px;
}
</style>

<div class="sidebar">
  <h3>Admin Panel</h3>
  <a href="admin_dashboard.php"> Dashboard</a>
    <a href="admin_profile.php">View Profile</a>
  <a href="admin_products.php">View Products</a>
  <a href="admin_purchases.php">Purchases</a>
  <a href="admin_users.php">View Users</a>
  <a href="logout.php">Logout</a>
</div>
