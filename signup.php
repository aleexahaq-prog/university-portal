<?php
include 'db.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user, $pass);
    if ($stmt->execute()) {
        header("Location: login.php?signup=success");
        exit();
    } else {
        $message = "Error: Username might already exist.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - University Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #f8fafc; --card: #fff; --text: #1e293b; --border: #e2e8f0; --primary: #1e293b; }
        body { background: var(--bg); color: var(--text); font-family: 'Inter', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { width: 100%; max-width: 400px; background: var(--card); padding: 30px; border: 1px solid var(--border); border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        h2 { margin-bottom: 20px; font-size: 20px; text-align: center; }
        .form-group { margin-bottom: 15px; display: flex; flex-direction: column; }
        label { font-size: 13px; font-weight: 500; margin-bottom: 5px; }
        input { padding: 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; }
        button { width: 100%; padding: 10px; background: var(--primary); color: #fff; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; margin-top: 10px; }
        .error { color: #ef4444; font-size: 13px; margin-bottom: 10px; text-align: center; }
        .links { text-align: center; margin-top: 15px; font-size: 13px; }
        .links a { color: #3b82f6; text-decoration: none; }
    </style>
</head>
<body>
<div class="box">
    <h2>Create Admin Account</h2>
    <?php if($message): ?><div class="error"><?php echo $message; ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group"><label>Username</label><input type="text" name="username" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
        <button type="submit">Sign Up</button>
    </form>
    <div class="links"><a href="login.php">Already have an account? Log In</a></div>
</div>
</body>
</html>