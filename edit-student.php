<?php
include 'db.php';
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }

$id = intval($_GET['id']);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $department = trim($_POST['department']);
    $semester = trim($_POST['semester']);
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("UPDATE students SET full_name = ?, department = ?, semester = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $full_name, $department, $semester, $email, $id);
    $stmt->execute();
    header("Location: database.php");
    exit();
}

$res = $conn->query("SELECT * FROM students WHERE id = $id");
$student = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Edit Student</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    body { background: #f8fafc; font-family: 'Inter', sans-serif; padding: 40px; }
    .box { max-width: 500px; margin: 0 auto; background: #fff; padding: 30px; border: 1px solid #e2e8f0; border-radius: 12px; }
    .form-group { margin-bottom: 15px; display: flex; flex-direction: column; }
    label { font-size: 13px; font-weight: 500; margin-bottom: 5px; }
    input { padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; }
    button { padding: 10px 20px; background: #1e293b; color: #fff; border: none; border-radius: 6px; cursor: pointer; }
</style>
</head>
<body>
<div class="box">
    <h2>Edit Student Record</h2>
    <form method="POST" style="margin-top:20px;">
        <div class="form-group"><label>Full Name</label><input type="text" name="full_name" value="<?php echo htmlspecialchars($student['full_name']); ?>" required></div>
        <div class="form-group"><label>Department</label><input type="text" name="department" value="<?php echo htmlspecialchars($student['department']); ?>" required></div>
        <div class="form-group"><label>Semester</label><input type="text" name="semester" value="<?php echo htmlspecialchars($student['semester']); ?>" required></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required></div>
        <button type="submit">Update Record</button>
    </form>
</div>
</body>
</html>