<?php
include 'config.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $captcha_input = $_POST['captcha'];
    $role = 'customer'; // Fixed role

    // ✅ CAPTCHA Check
    if (!isset($_SESSION['captcha']) || strtolower($captcha_input) !== strtolower($_SESSION['captcha'])) {
        echo json_encode(["success" => false, "message" => "Incorrect CAPTCHA! Please try again."]);
        exit();
    }

    // Optional: clear CAPTCHA
    unset($_SESSION['captcha']);

    // ✅ Email existence check with prepared statement
    $stmt = $conn->prepare("SELECT user_id FROM agro_users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Email already registered! Please log in."]);
        $stmt->close();
        exit();
    }
    $stmt->close();

    // ✅ Passwords match
    if ($password !== $confirm_password) {
        echo json_encode(["success" => false, "message" => "Passwords do not match!"]);
        exit();
    }

    // ✅ Password strength
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        echo json_encode([
            "success" => false,
            "message" => "Password must be at least 8 characters and include uppercase, lowercase, number, and special character."
        ]);
        exit();
    }

    // ✅ Insert user with hashed password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO agro_users (name, email, password, role, contact) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $hashed_password, $role, $contact);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Registration successful! You can now log in."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error occurred during registration. Please try again."]);
    }

    $stmt->close();
}
?>
