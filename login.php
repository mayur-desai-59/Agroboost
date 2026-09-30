<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header('Content-Type: application/json');

    // Collect and sanitize input
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $captcha_input = $_POST['captcha'];

    // ✅ CAPTCHA Check
    if (!isset($_SESSION['captcha']) || strtolower($captcha_input) !== strtolower($_SESSION['captcha'])) {
        echo json_encode([
            "success" => false,
            "message" => "Incorrect CAPTCHA! Please try again."
        ]);
        exit();
    }

    // Optional: clear CAPTCHA from session after check
    unset($_SESSION['captcha']);

    // ✅ Use prepared statements for safer DB interaction
    $stmt = $conn->prepare("SELECT * FROM agro_users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // ❌ User doesn't exist
    if (!$user) {
        echo json_encode([
            "success" => false,
            "message" => "User does not exist. Please register first!"
        ]);
        exit();
    }

    // ❌ Wrong password
    if (!password_verify($password, $user['password'])) {
        echo json_encode([
            "success" => false,
            "message" => "Incorrect password. Please try again."
        ]);
        exit();
    }

    // ❌ Admin role check
    $admin_email = 'saiagrofertilizers@gmail.com';
    if ($role === 'admin' && $email !== $admin_email) {
        echo json_encode([
            "success" => false,
            "message" => "Unauthorized: Only the official admin can log in as admin."
        ]);
        exit();
    }

    // ❌ Role mismatch
    if ($user['role'] !== $role) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid role selected for this user."
        ]);
        exit();
    }

    // ✅ All checks passed — set session data
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['email'] = $user['email'];

    // ✅ Prevent session fixation
    session_regenerate_id(true);

    // Optional: Log login (pseudo-code, implement as needed)
    // logLoginAttempt($email, $user['user_id'], true, $_SERVER['REMOTE_ADDR']);

    // ✅ Redirection logic
    if ($user['force_change_password']) {
        echo json_encode([
            "success" => true,
            "redirect" => "change_password.html"
        ]);
    } elseif ($user['role'] === 'admin') {
        echo json_encode([
            "success" => true,
            "redirect" => "admin_dashboard.php"
        ]);
    } else {
        echo json_encode([
            "success" => true,
            "redirect" => "dashboard.php"
        ]);
    }
}
?>
