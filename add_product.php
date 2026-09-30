<?php
include 'config.php';

$msg = ""; // Message for success or error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $kg = $_POST['kg'];
    $stock_status = $_POST['stock_status'];
    $use_by = $_POST['use_by'];

    // Image upload handling
    $image_url = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";  // Folder where images will be stored
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $image_url = $target_file;

        // Move uploaded file to the 'uploads' folder
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $msg = "Error uploading image.";
        }
    }

    // Insert data into database
    $sql = "INSERT INTO agro_products (name, description, price, kg, stock_status, image_url, use_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisss", $name, $description, $price, $kg, $stock_status, $image_url, $use_by);

    if ($stmt->execute()) {
        $msg = "Product added successfully!";
        header("Location: admin_products.php"); // Redirect to view products page
        exit();
    } else {
        $msg = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
<?php include 'admin_sidebar.php'; ?>
<?php include 'admin_header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #28a745;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
        }
        input, textarea, select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
        }
        button {
            margin-top: 15px;
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #218838;
        }
        .message {
            text-align: center;
            color: red;
            margin-bottom: 10px;
        }
        .back-btn {
            display: block;
            text-align: center;
            margin-top: 15px;
            padding: 10px;
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
    <div class="container">
        <h2>Add New Product</h2>
        <p class="message"><?php echo $msg; ?></p>
        
        <form method="post" enctype="multipart/form-data">
            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Description:</label>
            <textarea name="description" required></textarea>

            <label>Price:</label>
            <input type="number" name="price" step="0.01" required>

            <label>KG</label>
            <input type="number" name="kg" required>

            <label>Stock Status:</label>
            <select name="stock_status">
                <option value="In Stock">In Stock</option>
                <option value="Out of Stock">Out of Stock</option>
            </select>

            <label>Product Image:</label>
            <input type="file" name="image" accept="image/*">

            <label>Use By (Expiry Date):</label>
            <input type="date" name="use_by">

            <button type="submit">Add Product</button>
        </form>
        <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
