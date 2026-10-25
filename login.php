<?php
include 'db.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_pass);
        $stmt->fetch();
        
        if (password_verify($pass, $hashed_pass)) {
            $_SESSION['admin'] = $user;
            session_write_close();
            header("Location: index.php");
            exit();
        } else {
            $error = "Incorrect password! Please try again.";
        }
    } else {
        $error = "Admin username not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - University Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f8fafc;
            --card: #fff;
            --text: #1e293b;
            --border: #e2e8f0;
            --primary: #1e293b;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background: var(--bg); color: var(--text); display: flex; justify-content: center; align-items: center; height: 100vh; }
        .box { width: 100%; max-width: 400px; background: var(--card); padding: 30px; border: 1px solid var(--border); border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        h2 { margin-bottom: 5px; font-size: 20px; text-align: center; }
        p.subtitle { text-align: center; font-size: 13px; color: #64748b; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; display: flex; flex-direction: column; }
        label { font-size: 13px; font-weight: 500; margin-bottom: 5px; }
        input { padding: 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; width: 100%; }
        input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        button { width: 100%; padding: 10px; background: var(--primary); color: #fff; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; margin-top: 10px; }
        button:hover { background: #0f172a; }
        .error { color: #ef4444; font-size: 13px; margin-bottom: 15px; text-align: center; background: #fee2e2; padding: 10px; border-radius: 6px; border: 1px solid #fecaca; }
        .links { text-align: center; margin-top: 18px; font-size: 13px; display: flex; flex-direction: column; gap: 8px; }
        .links a { color: #3b82f6; text-decoration: none; }
        .links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="box">
    <h2>Admin Portal Login</h2>
    <p class="subtitle">Sign in to manage university database records</p>

    <?php if(!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Admin Log In</button>
    </form>

    <div class="links">
        <a href="signup.php">Create Admin Account</a>
        <a href="student-login.php" style="color: #059669; font-weight: 500;">&rarr; Switch to Student Login Portal</a>
        <a href="reset-password.php">Reset Password</a>
    </div>
</div>
</body>
</html>