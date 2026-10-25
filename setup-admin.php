<?php
include 'db.php';

$username = 'admin';
$password = '12345';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if admin already exists
$check = $conn->prepare("SELECT id FROM admins WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    // Update existing admin password
    $update = $conn->prepare("UPDATE admins SET password = ? WHERE username = ?");
    $update->bind_param("ss", $hashed_password, $username);
    $update->execute();
    echo "<h3 style='color:green; font-family:sans-serif;'>Admin password successfully updated!</h3>";
} else {
    // Insert new admin
    $insert = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $insert->bind_param("ss", $username, $hashed_password);
    $insert->execute();
    echo "<h3 style='color:green; font-family:sans-serif;'>Admin account created successfully!</h3>";
}

echo "<p style='font-family:sans-serif;'>Username: <b>admin</b><br>Password: <b>12345</b></p>";
echo "<br><a href='login.php' style='font-size:16px; font-family:sans-serif;'>Click here to go to Login Page</a>";
?>