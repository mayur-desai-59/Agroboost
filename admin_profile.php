<?php
include 'config.php';
include 'admin_sidebar.php';
include 'admin_header.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$msg = '';
$admin_data = [];

if ($user_id) {
    // Handle profile update
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
		$contact = $_POST['contact'];
        $email = $_POST['email'];
		
        $profile_pic = '';

        // Image upload
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
            $target_dir = "uploads/";
            $image_name = basename($_FILES["profile_pic"]["name"]);
            $target_file = $target_dir . $image_name;

            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                $profile_pic = $target_file;
            }
        }

        // Update query
        if ($profile_pic) {
            $stmt = $conn->prepare("UPDATE agro_users SET name = ?, contact = ?, email = ?, profile_pic = ? WHERE user_id = ?");
$stmt->bind_param("ssssi", $name, $contact, $email, $profile_pic, $user_id);

        } else {
           $stmt = $conn->prepare("UPDATE agro_users SET name = ?, contact = ?, email = ? WHERE user_id = ?");
$stmt->bind_param("sssi", $name, $contact, $email, $user_id);

        }

        if ($stmt->execute()) {
            $msg = "Profile updated successfully.";
        } else {
            $msg = "Error updating profile: " . $stmt->error;
        }

        $stmt->close();
    }

    // Fetch updated data
    $stmt = $conn->prepare("SELECT * FROM agro_users WHERE user_id = ? AND role = 'admin'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin_data = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile</title>
    <style>
        .profile-container {
            max-width: 600px;
            margin: 80px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .profile-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #28a745;
        }
        .profile-pic {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            display: block;
            margin: 0 auto 20px auto;
        }
        form label {
            display: block;
            margin-top: 10px;
        }
        form input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        .btn {
            margin-top: 15px;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .btn:hover {
            background-color: #218838;
        }
        .message {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Admin Profile</h2>
        <?php if ($msg): ?>
            <p class="message"><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>

        <?php if ($admin_data): ?>
            <img src="<?= $admin_data['profile_pic'] ? htmlspecialchars($admin_data['profile_pic']) : 'default-avatar.png' ?>" class="profile-pic" alt="Profile Picture">

            <form method="post" enctype="multipart/form-data">
                <label>Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($admin_data['name']) ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($admin_data['email']) ?>" required>
				
				 <label>Phone No:</label>
                <input type="text" name="contact" value="<?= htmlspecialchars($admin_data['contact']) ?>" required>

                <label>Profile Picture:</label>
                <input type="file" name="profile_pic" accept="image/*">

                <button class="btn" type="submit">Update Profile</button>
				        <a href="change_password.php" class="back-link">Change Password</a>

            </form>
        <?php else: ?>
            <p style="color: red;">Admin profile not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
