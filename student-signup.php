<?php
include 'db.php';
$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = trim($_POST['student_id']);
    $registration_number = trim($_POST['registration_number']);
    $full_name = trim($_POST['full_name']);
    $father_name = trim($_POST['father_name']);
    $department = trim($_POST['department']);
    $semester = trim($_POST['semester']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if Student ID already exists
    $check = $conn->prepare("SELECT id FROM students WHERE student_id = ?");
    $check->bind_param("s", $student_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error_msg = "Error: This Student ID is already registered.";
    } else {
        // Insert new student account into database
        $sql = "INSERT INTO students (student_id, registration_number, full_name, father_name, department, semester, email, phone, password) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $student_id, $registration_number, $full_name, $father_name, $department, $semester, $email, $phone, $password);

        if ($stmt->execute()) {
            $success_msg = "Account created successfully! <a href='student-login.php'>Log in here</a>";
        } else {
            $error_msg = "Database Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Student Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #f8fafc; --card: #fff; --text: #1e293b; --border: #e2e8f0; --primary: #1e293b; }
        body { background: var(--bg); color: var(--text); font-family: 'Inter', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .box { width: 100%; max-width: 450px; background: var(--card); padding: 30px; border: 1px solid var(--border); border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        h2 { margin-bottom: 5px; font-size: 20px; text-align: center; }
        p.subtitle { text-align: center; font-size: 13px; color: #64748b; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; display: flex; flex-direction: column; }
        label { font-size: 13px; font-weight: 500; margin-bottom: 5px; }
        input, select { padding: 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; width: 100%; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: var(--primary); color: #fff; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; margin-top: 10px; }
        .alert { padding: 10px; border-radius: 6px; font-size: 13px; margin-bottom: 15px; text-align: center; }
        .alert.success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert.error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .links { text-align: center; margin-top: 15px; font-size: 13px; }
        .links a { color: #3b82f6; text-decoration: none; }
    </style>
</head>
<body>
<div class="box">
    <h2>Student Sign Up</h2>
    <p class="subtitle">Create your portal account and database record</p>

    <?php if(!empty($success_msg)): ?><div class="alert success"><?php echo $success_msg; ?></div><?php endif; ?>
    <?php if(!empty($error_msg)): ?><div class="alert error"><?php echo $error_msg; ?></div><?php endif; ?>

    <form method="POST">
        <div class="form-group"><label>Student ID *</label><input type="text" name="student_id" placeholder="e.g. CS-001" required></div>
        <div class="form-group"><label>Registration Number *</label><input type="text" name="registration_number" placeholder="e.g. 2024-WUM-123" required></div>
        <div class="form-group"><label>Full Name *</label><input type="text" name="full_name" required></div>
        <div class="form-group"><label>Father's Name *</label><input type="text" name="father_name" required></div>
        <div class="form-group">
            <label>Department *</label>
            <select name="department" required>
                <option value="">Select Department</option>
                <option value="Computer Science">Computer Science</option>
                <option value="Biotechnology">Biotechnology</option>
                <option value="Management Sciences">Management Sciences</option>
                <option value="English">English</option>
            </select>
        </div>
        <div class="form-group">
            <label>Semester *</label>
            <select name="semester" required>
                <option value="">Select Semester</option>
                <option value="1st Semester">1st Semester</option>
                <option value="2nd Semester">2nd Semester</option>
                <option value="3rd Semester">3rd Semester</option>
                <option value="4th Semester">4th Semester</option>
                <option value="5th Semester">5th Semester</option>
                <option value="6th Semester">6th Semester</option>
                <option value="7th Semester">7th Semester</option>
                <option value="8th Semester">8th Semester</option>
            </select>
        </div>
        <div class="form-group"><label>Email Address *</label><input type="email" name="email" required></div>
        <div class="form-group"><label>Phone Number *</label><input type="text" name="phone" required></div>
        <div class="form-group"><label>Create Password *</label><input type="password" name="password" required></div>
        <button type="submit">Sign Up & Register</button>
    </form>
    <div class="links"><a href="student-login.php">Already have an account? Log In</a></div>
</div>
</body>
</html>