<?php
include 'config.php';

if (isset($_POST['send_temp'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $checkUser = mysqli_query($conn, "SELECT * FROM agro_users WHERE email='$email'");

    if (mysqli_num_rows($checkUser) > 0) {
        // Generate a random 8-character temporary password
        $temp_password = bin2hex(random_bytes(4)); 
        $hashed_password = password_hash($temp_password, PASSWORD_BCRYPT);

        // Update the temporary password in DB
	$update = mysqli_query($conn, "UPDATE agro_users SET password='$hashed_password', force_change_password=1 WHERE email='$email'");

        if ($update) {
    echo "<script>alert('Your temporary password is: $temp_password. Please log in and change your password.'); window.location.href='main.html';</script>";
} else {
    echo "<script>alert('Error generating temporary password.'); window.location.href='forgot_password.html';</script>";
}
} else {
    echo "<script>alert('No account found with this email.'); window.location.href='forgot_password.html';</script>";
}
}
?>
