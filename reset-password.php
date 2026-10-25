<?php
include 'db.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $new_pass, $user);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $msg = "Password updated successfully! <a href='login.php'>Login here</a>";
    } else {
        $msg = "Username not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Reset Password</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    body { background: #f8fafc; font-family: 'Inter', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
    .box { width: 100%; max-width: 400px; background: #fff; padding: 30px; border: 1px solid #e2e8f0; border-radius: 12px; }
    h2 { margin-bottom: 20px; font-size: 20px; text-align: center; }
    .form-group { margin-bottom: 15px; display: flex; flex-direction: column; }
    label { font-size: 13px; font-weight: 500; margin-bottom: 5px; }
    input { padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; }
    button { width: 100%; padding: 10px; background: #1e293b; color: #fff; border: none; border-radius: 6px; cursor: pointer; margin-top: 10px; }
    .msg { font-size: 13px; text-align: center; margin-bottom: 10px; }
</style>
</head>
<body>
<div class="box">
    <h2>Reset Password</h2>
    <?php if($msg): ?><div class="msg"><?php echo $msg; ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group"><label>Username</label><input type="text" name="username" required></div>
        <div class="form-group"><label>New Password</label><input type="password" name="new_password" required></div>
        <button type="submit">Update Password</button>
    </form>
    <div style="text-align:center; margin-top:15px;"><a href="login.php" style="font-size:13px; color:#3b82f6; text-decoration:none;">Back to Login</a></div>
</div>
</body>
</html>