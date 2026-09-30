<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: main.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$user_query = "SELECT name, email FROM agro_users WHERE user_id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

// Fetch cart items
$cart_query = "SELECT * FROM agro_cart WHERE user_id = $user_id";
$cart_result = mysqli_query($conn, $cart_query);

// Handle confirm order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    while ($row = mysqli_fetch_assoc($cart_result)) {
        $product_id = $row['product_id'];
        $product_name = $row['product_name'];
        $image_url = $row['image_url'];
        $price = $row['price'];
        $quantity = $row['quantity'];
        $total = $price * $quantity;

        $insert = "INSERT INTO agro_orders (user_id, product_id, product_name, image_url, price, quantity, ordered_at) 
                   VALUES ('$user_id', '$product_id', '$product_name', '$image_url', '$price', '$quantity', NOW())";
        mysqli_query($conn, $insert);
    }

    // Clear cart after order
    mysqli_query($conn, "DELETE FROM agro_cart WHERE user_id = $user_id");

    echo "<script>alert('Order Confirmed! Please bring your Aadhar card and visit our shop.'); window.location.href='dashboard.php';</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Summary - AgroBoost</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .summary-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #28a745;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        .total {
            font-weight: bold;
            color: #28a745;
        }

        .buttons {
            text-align: center;
            margin-top: 30px;
        }

        .buttons button {
            padding: 10px 20px;
            margin: 0 10px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }

        .confirm {
            background-color: #28a745;
            color: white;
        }

        .cancel {
            background-color: #dc3545;
            color: white;
        }

        .map-box {
            margin-top: 30px;
            text-align: center;
        }

        iframe {
            width: 100%;
            height: 300px;
            border: 0;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="summary-box">
    <h2>Order Summary</h2>

    <p><strong>Customer:</strong> <?= htmlspecialchars($user['name']) ?> |</p>
	<p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>

    <table>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Subtotal</th>
        </tr>
        <?php
        mysqli_data_seek($cart_result, 0);
        $grand_total = 0;
        while ($item = mysqli_fetch_assoc($cart_result)):
            $subtotal = $item['price'] * $item['quantity'];
            $grand_total += $subtotal;
        ?>
        <tr>
            <td><img src="<?= htmlspecialchars($item['image_url']) ?>" width="60"></td>
            <td><?= htmlspecialchars($item['product_name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>₹<?= $item['price'] ?></td>
            <td>₹<?= $subtotal ?></td>
        </tr>
        <?php endwhile; ?>
        <tr>
            <td colspan="4" class="total">Total</td>
            <td class="total">₹<?= $grand_total ?></td>
        </tr>
    </table>

    <div class="buttons">
        <form method="post">
            <button type="submit" name="confirm_order" class="confirm">Confirm Order</button>
            <a href="view_cart.php"><button type="button" class="cancel">Cancel</button></a>
        </form>
    </div>

    <div class="map-box">
        <h3>Pickup Location</h3>
        <p>Bring your Aadhar card and visit the shop to collect your order.</p>
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3846.186092282075!2d74.48867487452678!3d15.420504285168892!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bbf10a91a3735b5%3A0x7f2a551d89c52c78!2sSai%2C%20Computer%20Gramone%20center%20Ramngar!5e0!3m2!1sen!2sin!4v1745576052207!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

</body>
</html>
