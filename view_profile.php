<?php
session_start(); // Make sure session is started

// Debugging: Check session data
//var_dump($_SESSION);  // <-- Check the session data here

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: main.html");
    exit();
}

include("config.php");

$user_id = $_SESSION['user_id']; // Get user_id from session

$query = "SELECT name, email, contact FROM agro_users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $contact);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - AgroBoost</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6fff7;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #28a745;
        }
        .profile-info {
            margin-top: 20px;
        }
        .profile-info label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            color: #2e7d32;
        }
        .profile-info span {
            display: block;
            margin-top: 5px;
            color: #333;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #28a745;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>My Profile</h2>
        <div class="profile-info">
            <label>Username:</label>
            <span><?php echo htmlspecialchars($name); ?></span>

            <label>Email:</label>
            <span><?php echo htmlspecialchars($email); ?></span>

            <label>Contact:</label>
            <span><?php echo htmlspecialchars($contact); ?></span>
			 </div>
        <a href="change_password.php" class="back-link">Change Password</a>
    </div>
	 </div>
        <a href="logout.php" class="back-link">Logout</a>
    </div>
        </div>
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>
</html>
