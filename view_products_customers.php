<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: main.html");
    exit();
}

$query = "SELECT * FROM agro_products";
$result = mysqli_query($conn, $query);

// HANDLE POPUP FLAG
$cartAdded = false;
$productName = "";

if (isset($_SESSION['cart_added'])) {
    $cartAdded = true;
    $productName = $_SESSION['added_product_name'] ?? "";
    unset($_SESSION['cart_added'], $_SESSION['added_product_name']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Products - AgroBoost</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f9f9f9;
        }
        .navbar {
            background-color: #28a745;
            padding: 15px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
            color: #28a745;
        }

        .products-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
            gap: 20px;
        }

        .product-card {
            background-color: white;
            border-radius: 10px;
            width: 250px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.2s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }

        .product-details {
            padding: 15px;
        }

        .product-details h3 {
            margin: 0 0 10px;
            color: #333;
        }

        .product-details p {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        .price {
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
        }

        .add-btn {
            background-color: #28a745;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .add-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="navbar">
    <strong>AgroBoost - Products</strong>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="view_cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<h1>Our Products</h1>

<div class="products-container">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="product-card">
            <?php if (!empty($row['image_url'])): ?>
                <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
            <?php else: ?>
                <img src="placeholder.jpg" alt="No Image Available">
            <?php endif; ?>
            <div class="product-details">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p><?= htmlspecialchars($row['description']) ?></p>
				                <p><?= htmlspecialchars($row['kg']) ?></p>

                <div class="price">₹<?= htmlspecialchars($row['price']) ?></div>
                <form method="post" action="add_to_cart.php">
    <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
    <input type="hidden" name="image_url" value="<?= htmlspecialchars($row['image_url']) ?>">
    <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']) ?>">
    <input type="hidden" name="price" value="<?= htmlspecialchars($row['price']) ?>">
    <button type="submit" class="add-btn"><i class="fas fa-cart-plus"></i> Add to Cart</button>
</form>

            </div>
        </div>
    <?php endwhile; ?>
</div>
<!-- Modal Popup -->
<div id="cartPopup" class="popup-overlay" style="display: <?= $cartAdded ? 'flex' : 'none' ?>;">
  <div class="popup-box">
    <h3>🛒 Product Added!</h3>
    <p><strong><?= htmlspecialchars($productName) ?></strong> has been added to your cart.</p>
    <div class="popup-actions">
      <a href="view_cart.php" class="btn">View Cart</a>
      <button onclick="closePopup()" class="btn outline">Add More Products</button>
    </div>
  </div>
</div>

<style>
  .popup-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100vw; height: 100vh;
    background: rgba(0, 0, 0, 0.4);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  }

  .popup-box {
    background: #fff;
    padding: 25px 30px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
  }

  .popup-box h3 {
    margin-bottom: 10px;
    color: #28a745;
  }

  .popup-actions {
    margin-top: 20px;
  }

  .btn {
    background-color: #28a745;
    color: white;
    padding: 8px 16px;
    margin: 5px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
  }

  .btn.outline {
    background: none;
    color: #28a745;
    border: 2px solid #28a745;
  }

  .btn:hover {
    opacity: 0.9;
  }
</style>

<script>
  function closePopup() {
    document.getElementById("cartPopup").style.display = "none";
  }
</script>

</body>
</html>
