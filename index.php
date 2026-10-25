<?php
include 'db.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = trim($_POST['student_id']);
    $registration_number = trim($_POST['registration_number']);
    $full_name = trim($_POST['full_name']);
    $father_name = trim($_POST['father_name']);
    $cnic = trim($_POST['cnic']);
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $department = $_POST['department'];
    $degree_level = $_POST['degree_level'];
    $degree_program = trim($_POST['degree_program']);
    $batch = trim($_POST['batch']);
    $academic_session = $_POST['academic_session'];
    $semester = $_POST['semester'];
    $enrollment_status = $_POST['enrollment_status'];
    $hobby = trim($_POST['hobby']);

    $sql = "INSERT INTO students (student_id, registration_number, full_name, father_name, cnic, gender, dob, email, phone, address, department, degree_level, degree_program, batch, academic_session, semester, enrollment_status, hobby) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssssss", $student_id, $registration_number, $full_name, $father_name, $cnic, $gender, $dob, $email, $phone, $address, $department, $degree_level, $degree_program, $batch, $academic_session, $semester, $enrollment_status, $hobby);

    if ($stmt->execute()) {
        $success_message = "Student record saved successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Dashboard - University Student Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #f8fafc; --card: #fff; --text: #1e293b; --muted: #64748b; --primary: #1e293b; --border: #e2e8f0; }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg); color: var(--text); padding: 30px; }
        .container { max-width: 950px; margin: 0 auto; background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 40px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .nav-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid var(--border); padding-bottom: 15px; }
        .nav-bar h2 { font-size: 22px; }
        .nav-links a { margin-left: 15px; text-decoration: none; color: #3b82f6; font-weight: 500; font-size: 14px; }
        .nav-links a.logout { color: #ef4444; }
        .alert { padding: 12px 16px; border-radius: 6px; font-size: 14px; margin-bottom: 24px; }
        .alert.success { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert.error { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .section-title { font-size: 15px; font-weight: 600; margin: 24px 0 16px 0; padding-bottom: 6px; border-bottom: 1px solid var(--border); }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group.full-width { grid-column: span 2; }
        label { font-size: 13px; font-weight: 500; margin-bottom: 6px; }
        input, select, textarea { width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; background: #fff; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        textarea { height: 80px; resize: vertical; }
        .btn-container { display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; }
        button { padding: 10px 20px; font-size: 14px; font-weight: 500; border-radius: 6px; cursor: pointer; border: none; }
        .btn-submit { background: var(--primary); color: #fff; }
        .btn-clear { background: #f1f5f9; color: var(--text); border: 1px solid var(--border); }
    </style>
</head>
<body>
<div class="container">
    <div class="nav-bar">
        <h2>Dashboard - Add Student</h2>
        <div class="nav-links">
            <span style="font-size:13px; color:var(--muted);">User: <strong><?php echo $_SESSION['admin']; ?></strong></span>
            <a href="index.php">Dashboard</a>
            <a href="database.php">View Database</a>
            <a href="logout.php" class="logout">Log Out</a>
        </div>
    </div>

    <?php if($success_message): ?><div class="alert success"><?php echo $success_message; ?></div><?php endif; ?>
    <?php if($error_message): ?><div class="alert error"><?php echo $error_message; ?></div><?php endif; ?>

    <form method="POST">
        <div class="section-title" style="margin-top:0;">Academic Information</div>
        <div class="form-grid">
            <div class="form-group"><label>Department *</label><select name="department" required><option value="">Select</option><option value="Computer Science">Computer Science</option><option value="Biotechnology">Biotechnology</option><option value="Management Sciences">Management Sciences</option><option value="English">English</option></select></div>
            <div class="form-group"><label>Degree Level *</label><select name="degree_level" required><option value="">Select</option><option value="BS (4 Years)">BS (4 Years)</option><option value="BS (5th Semester)">BS (5th Semester)</option><option value="M.Phil">M.Phil</option></select></div>
            <div class="form-group"><label>Degree Program *</label><input type="text" name="degree_program" placeholder="e.g. BS CS" required></div>
            <div class="form-group"><label>Batch *</label><input type="text" name="batch" placeholder="e.g. 2024-2028" required></div>
            <div class="form-group"><label>Academic Session *</label><select name="academic_session" required><option value="">Select</option><option value="Fall">Fall</option><option value="Spring">Spring</option></select></div>
            <div class="form-group"><label>Semester *</label><select name="semester" required><option value="">Select</option><option value="1st Semester">1st</option><option value="2nd Semester">2nd</option><option value="3rd Semester">3rd</option><option value="4th Semester">4th</option><option value="5th Semester">5th</option><option value="6th Semester">6th</option><option value="7th Semester">7th</option><option value="8th Semester">8th</option></select></div>
            <div class="form-group"><label>Enrollment Status *</label><select name="enrollment_status" required><option value="">Select</option><option value="Regular">Regular</option><option value="Suspended">Suspended</option></select></div>
            <div class="form-group"><label>Hobby (Optional)</label><input type="text" name="hobby" placeholder="e.g. Coding"></div>
        </div>

        <div class="section-title">Official Identification</div>
        <div class="form-grid">
            <div class="form-group"><label>Student ID *</label><input type="text" name="student_id" placeholder="CS-001" required></div>
            <div class="form-group"><label>Registration Number *</label><input type="text" name="registration_number" placeholder="2024-WUM-123" required></div>
        </div>

        <div class="section-title">Personal & Contact Information</div>
        <div class="form-grid">
            <div class="form-group"><label>Full Name *</label><input type="text" name="full_name" required></div>
            <div class="form-group"><label>Father's Name *</label><input type="text" name="father_name" required></div>
            <div class="form-group"><label>CNIC Number *</label><input type="text" name="cnic" required></div>
            <div class="form-group"><label>Gender *</label><select name="gender" required><option value="">Select</option><option value="Female">Female</option><option value="Male">Male</option></select></div>
            <div class="form-group"><label>Date of Birth *</label><input type="date" name="dob" required></div>
            <div class="form-group"><label>Email Address *</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Phone Number *</label><input type="text" name="phone" required></div>
            <div class="form-group full-width"><label>Permanent Address *</label><textarea name="address" required></textarea></div>
        </div>

        <div class="btn-container">
            <button type="reset" class="btn-clear">Clear Form</button>
            <button type="submit" class="btn-submit">Save Record</button>
        </div>
    </form>
</div>
</body>
</html>