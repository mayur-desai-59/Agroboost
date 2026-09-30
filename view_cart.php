<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: main.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $new_quantity = max(1, (int)$_POST['quantity']);
    mysqli_query($conn, "UPDATE agro_cart SET quantity = $new_quantity WHERE cart_id = $cart_id AND user_id = $user_id");
}

// Handle item removal
if (isset($_GET['remove'])) {
    $cart_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM agro_cart WHERE cart_id = $cart_id AND user_id = $user_id");
}

$query = "SELECT * FROM agro_cart WHERE user_id = $user_id ORDER BY added_at DESC";
$result = mysqli_query($conn, $query);

$total_price = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart - AgroBoost</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f5;
            margin: 0;
            padding: 0;
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
            margin: 30px 0;
            color: #28a745;
        }

        .cart-container {
            width: 90%;
            max-width: 1000px;
            margin: auto;
        }

        .cart-item {
            background-color: white;
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }

        .cart-item img {
            width: 120px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 20px;
        }

        .cart-details {
            flex: 1;
        }

        .cart-details h3 {
            margin: 0 0 10px;
            color: #333;
        }

        .cart-details p {
            margin: 5px 0;
        }

        .quantity-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        input[type="number"] {
            width: 60px;
            padding: 5px;
        }

        .btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #218838;
        }

        .remove-link {
            color: #dc3545;
            text-decoration: none;
            margin-left: 20px;
        }

        .remove-link:hover {
            text-decoration: underline;
        }

        .total {
            text-align: right;
            font-size: 20px;
            margin-top: 20px;
            font-weight: bold;
            color: #2e7d32;
        }

        .empty {
            text-align: center;
            font-size: 18px;
            margin-top: 50px;
            color: #777;
        }
		.place-order-btn {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
}

.place-order-btn:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>

<div class="navbar">
    <strong>AgroBoost - Your Cart</strong>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="view_products_customers.php">View Products</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<h1>Your Shopping Cart</h1>

<div class="cart-container">
<?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($result)): 
        $subtotal = $row['price'] * $row['quantity'];
        $total_price += $subtotal;
    ?>
        <div class="cart-item">
            <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
            <div class="cart-details">
                <h3><?= htmlspecialchars($row['product_name']) ?></h3>
                <p>Price: ₹<?= htmlspecialchars($row['price']) ?></p>
                <form method="post" class="quantity-form">
                    <input type="hidden" name="cart_id" value="<?= $row['cart_id'] ?>">
                    <label for="qty">Qty:</label>
                    <input type="number" name="quantity" value="<?= $row['quantity'] ?>" min="1">
                    <button type="submit" name="update_quantity" class="btn">Update</button>
                    <a href="?remove=<?= $row['cart_id'] ?>" class="remove-link">Remove</a>
                </form>
                <p>Subtotal: ₹<?= number_format($subtotal, 2) ?></p>
            </div>
        </div>
    <?php endwhile; ?>
    <div class="total">Total: ₹<?= number_format($total_price, 2) ?></div>
<?php else: ?>
    <div class="empty">
        🛒 Your cart is currently empty.<br>
        <a href="view_products_customers.php" style="color: #28a745; font-weight: bold; text-decoration: none; display: inline-block; margin-top: 10px;">
            Browse our products
        </a>
    </div>
<?php endif; ?>
<?php if (mysqli_num_rows($result) > 0): ?>
    <div style="text-align: center; margin-top: 20px;">
        <form action="order.php" method="post">
            <button type="submit" class="place-order-btn">Place Order</button>
        </form>
    </div>
	<div>           
        <a href="view_products_customers.php">Add more Products</a>

</div>
	<div>           
<?php endif; ?>

</div>

</body>
</html>
