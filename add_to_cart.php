<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: main.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $image_url = $_POST['image_url'];
    $price = $_POST['price'];
    $quantity = 1;

    // Check if product already in cart
    $check_query = "SELECT * FROM agro_cart WHERE user_id = $user_id AND product_id = $product_id";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $update_query = "UPDATE agro_cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id";
        mysqli_query($conn, $update_query);
    } else {
        $insert_query = "INSERT INTO agro_cart (user_id, product_id, product_name, image_url, price, quantity, added_at) 
                         VALUES ($user_id, $product_id, '$product_name', '$image_url', $price, $quantity, NOW())";
        mysqli_query($conn, $insert_query);
    }

    // Set session flag for popup
    $_SESSION['cart_added'] = true;
    $_SESSION['added_product_name'] = $product_name;

    // Redirect back to products page
    header("Location: view_products_customers.php");
    exit();
}
?>
