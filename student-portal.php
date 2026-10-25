<?php
include 'db.php';
if (!isset($_SESSION['student_logged'])) {
    header("Location: student-login.php");
    exit();
}

$db_id = $_SESSION['student_db_id'];

// Handle Student Delete Record
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $conn->query("DELETE FROM students WHERE id = $db_id");
    session_destroy();
    header("Location: student-login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $db_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>My Student Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #f8fafc; --card: #fff; --text: #1e293b; --muted: #64748b; --border: #e2e8f0; }
        body { background: var(--bg); color: var(--text); padding: 30px; font-family: 'Inter', sans-serif; }
        .container { max-width: 800px; margin: 0 auto; background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .nav-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid var(--border); padding-bottom: 15px; }
        .nav-links a { margin-left: 15px; text-decoration: none; color: #ef4444; font-size: 14px; font-weight: 500; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px; font-size: 14px; }
        .info-item { background: #f8fafc; padding: 12px; border: 1px solid var(--border); border-radius: 6px; }
        .info-item span { display: block; font-size: 12px; color: var(--muted); margin-bottom: 4px; }
        .btn-container { display: flex; gap: 10px; }
        .btn { padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 500; border: none; cursor: pointer; }
        .btn-edit { background: #3b82f6; color: #fff; }
        .btn-delete { background: #ef4444; color: #fff; }
    </style>
</head>
<body>
<div class="container">
    <div class="nav-bar">
        <h2>Welcome, <?php echo htmlspecialchars($student['full_name']); ?>!</h2>
        <div class="nav-links">
            <a href="logout.php">Log Out</a>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-item"><span>Student ID</span> <strong><?php echo htmlspecialchars($student['student_id']); ?></strong></div>
        <div class="info-item"><span>Registration Number</span> <strong><?php echo htmlspecialchars($student['registration_number']); ?></strong></div>
        <div class="info-item"><span>Department</span> <strong><?php echo htmlspecialchars($student['department']); ?></strong></div>
        <div class="info-item"><span>Degree Program</span> <strong><?php echo htmlspecialchars($student['degree_program']); ?></strong></div>
        <div class="info-item"><span>Semester</span> <strong><?php echo htmlspecialchars($student['semester']); ?></strong></div>
        <div class="info-item"><span>Email Address</span> <strong><?php echo htmlspecialchars($student['email']); ?></strong></div>
        <div class="info-item"><span>Phone Number</span> <strong><?php echo htmlspecialchars($student['phone']); ?></strong></div>
        <div class="info-item"><span>Enrollment Status</span> <strong><?php echo htmlspecialchars($student['enrollment_status'] ?? 'Regular'); ?></strong></div>
    </div>

    <div class="btn-container">
        <a href="student-edit.php" class="btn btn-edit">Edit My Information</a>
        <a href="student-portal.php?action=delete" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete your record?')">Delete Record</a>
    </div>
</div>
</body>
</html>