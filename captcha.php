<?php
session_start();

// Generate a random 6-character CAPTCHA code
$captcha_code = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 0, 6);
$_SESSION['captcha'] = $captcha_code;

// Create an image
$width = 120;
$height = 40;
$image = imagecreate($width, $height);

// Colors
$bg_color = imagecolorallocate($image, 255, 255, 255); // White background
$text_color = imagecolorallocate($image, 0, 0, 0); // Black text
$line_color = imagecolorallocate($image, 200, 200, 200); // Light grey lines

// Add random lines for security
for ($i = 0; $i < 5; $i++) {
    imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $line_color);
}

// Add the CAPTCHA text
imagestring($image, 5, 30, 10, $captcha_code, $text_color);

// Output the image
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>
