<?php
include 'db.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = trim($_POST['student_id']);
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, student_id, password FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_student_id, $hashed_pass);
        $stmt->fetch();
        
        if (empty($hashed_pass) || password_verify($pass, $hashed_pass)) {
            $_SESSION['student_logged'] = $db_student_id;
            $_SESSION['student_db_id'] = $id;
            header("Location: student-portal.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Student ID not found in database!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Student Login - University Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #f8fafc; --card: #fff; --text: #1e293b; --border: #e2e8f0; --primary: #1e293b; }
        body { background: var(--bg); color: var(--text); font-family: 'Inter', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { width: 100%; max-width: 400px; background: var(--card); padding: 30px; border: 1px solid var(--border); border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        h2 { margin-bottom: 8px; font-size: 20px; text-align: center; }
        p.subtitle { text-align: center; font-size: 13px; color: #64748b; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; display: flex; flex-direction: column; }
        label { font-size: 13px; font-weight: 500; margin-bottom: 5px; }
        input { padding: 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; width: 100%; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: var(--primary); color: #fff; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; margin-top: 10px; }
        .error { color: #ef4444; font-size: 13px; margin-bottom: 15px; text-align: center; background: #fee2e2; padding: 10px; border-radius: 6px; border: 1px solid #fecaca; }
        .links { text-align: center; margin-top: 15px; font-size: 13px; }
        .links a { color: #3b82f6; text-decoration: none; }
    </style>
</head>
<body>
<div class="box">
    <h2>Student Portal</h2>
    <p class="subtitle">Log in to view and manage your record</p>
    <?php if(!empty($error)): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group"><label>Student ID</label><input type="text" name="student_id" placeholder="e.g. CS-001" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" placeholder="Enter password" required></div>
        <button type="submit">Student Log In</button>
    </form>
    <div class="links">
        <a href="student-signup.php">Don't have an account? Sign Up</a> | 
        <a href="login.php">Admin Login</a>
    </div>
</div>
</body>
</html>