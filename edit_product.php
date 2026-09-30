<?php
include 'config.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "SELECT * FROM agro_products where product_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if (!$product) {
        die("Product not found.");
    }
} else {
    die("Invalid product ID.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $kg = $_POST['kg'];
    $stock_status = $_POST['stock_status'];
    $image_url = $_POST['image_url'];
    $use_by = $_POST['use_by'];

    $sql = "UPDATE agro_products SET name=?, description=?, price=?, kg=?, stock_status=?, image_url=?, use_by=? WHERE product_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisssi", $name, $description, $price, $kg, $stock_status, $image_url, $use_by, $product_id);

    if ($stmt->execute()) {
        header("Location: admin_products.php");
        exit();
    } else {
        echo "Error updating product: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        .container {
            width: 60%;
            background: #fff;
            padding: 20px;
            margin: 60px auto;
            margin-left: 280px; /* Push right to avoid sidebar overlap */
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: green;
            text-align: center;
        }
        label {
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: green;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .back-button {
            background-color: #ccc;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <?php include 'admin_sidebar.php'; ?>

    <div class="container">
        <h2>Edit Product</h2>
        <form method="post">
            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

            <label>Description:</label>
            <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

            <label>Price:</label>
            <input type="number" name="price" value="<?php echo $product['price']; ?>" step="0.01" required>

            <label>KG</label>
            <input type="number" name="kg" value="<?php echo $product['kg']; ?>" required>

            <label>Stock Status:</label>
            <select name="stock_status">
                <option value="In Stock" <?php echo ($product['stock_status'] == 'In Stock') ? 'selected' : ''; ?>>In Stock</option>
                <option value="Out of Stock" <?php echo ($product['stock_status'] == 'Out of Stock') ? 'selected' : ''; ?>>Out of Stock</option>
            </select>

            <label>Image URL:</label>
            <input type="text" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>">

            <label>Use By (Expiry Date):</label>
            <input type="date" name="use_by" value="<?php echo $product['use_by']; ?>">

            <button type="submit">Update Product</button>
        </form>
        <br>
    </div>
</body>
</html>
