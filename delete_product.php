<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM agro_products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Product deleted successfully!";
        header("Location: admin_products.php");
        exit();
    } else {
        echo "Error deleting product: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
