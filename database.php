<?php
include 'db.php';
if (!isset($_SESSION['admin'])) { 
    header("Location: login.php"); 
    exit(); 
}

// Handle Delete Action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM students WHERE id = $id");
    header("Location: database.php");
    exit();
}

// Handle Search and Sorting Parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : "id";
$order = isset($_GET['order']) && $_GET['order'] == 'DESC' ? 'DESC' : 'ASC';

// Allowed columns to prevent SQL injection
$allowed_columns = ['id', 'student_id', 'full_name', 'department', 'semester'];
if (!in_array($sort_by, $allowed_columns)) {
    $sort_by = 'id';
}

// Build Query
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id LIKE ? OR full_name LIKE ? OR department LIKE ? ORDER BY $sort_by $order");
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM students ORDER BY $sort_by $order");
}

// Toggle sort direction for links
$next_order = ($order == 'ASC') ? 'DESC' : 'ASC';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database View - University Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #f8fafc; --card: #fff; --text: #1e293b; --muted: #64748b; --border: #e2e8f0; --primary: #1e293b; }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background: var(--bg); color: var(--text); padding: 30px; }
        .container { max-width: 1150px; margin: 0 auto; background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .nav-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid var(--border); padding-bottom: 15px; }
        .nav-bar h2 { font-size: 20px; }
        .nav-links a { margin-left: 15px; text-decoration: none; color: #3b82f6; font-size: 14px; font-weight: 500; }
        .nav-links a.logout { color: #ef4444; }
        
        .controls-bar { display: flex; justify-content: space-between; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; }
        .search-bar { display: flex; gap: 10px; flex: 1; min-width: 280px; }
        .search-bar input { flex: 1; padding: 10px 14px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; }
        .search-bar input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        .btn-search { padding: 10px 20px; background: var(--primary); color: #fff; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; }
        .btn-reset { padding: 10px 15px; background: #f1f5f9; color: var(--text); border: 1px solid var(--border); border-radius: 6px; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; }

        .sort-controls { display: flex; align-items: center; gap: 10px; font-size: 13px; color: var(--muted); }
        .sort-controls select { padding: 9px; border: 1px solid var(--border); border-radius: 6px; font-size: 13px; background: #fff; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; }
        th, td { padding: 12px; border: 1px solid var(--border); text-align: left; }
        th { background: #f8fafc; font-weight: 600; color: var(--muted); }
        tr:hover { background: #f8fafc; }
        .btn { padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 500; }
        .btn-edit { background: #3b82f6; color: #fff; }
        .btn-delete { background: #ef4444; color: #fff; }
        .no-records { text-align: center; padding: 30px; color: var(--muted); font-size: 14px; }
        .sort-arrow { font-size: 11px; margin-left: 4px; color: #3b82f6; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <div class="nav-bar">
        <h2>Student Database Records</h2>
        <div class="nav-links">
            <span style="font-size:13px; color:var(--muted);">Admin: <strong><?php echo $_SESSION['admin']; ?></strong></span>
            <a href="index.php">Add Student</a>
            <a href="database.php">View Database</a>
            <a href="logout.php" class="logout">Log Out</a>
        </div>
    </div>

    <!-- Search & Sort Controls Bar -->
    <div class="controls-bar">
        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search ID, Name, or Department..." value="<?php echo htmlspecialchars($search); ?>">
            <input type="hidden" name="sort_by" value="<?php echo htmlspecialchars($sort_by); ?>">
            <input type="hidden" name="order" value="<?php echo htmlspecialchars($order); ?>">
            <button type="submit" class="btn-search">Search</button>
            <?php if(!empty($search)): ?>
                <a href="database.php" class="btn-reset">Reset</a>
            <?php endif; ?>
        </form>

        <div class="sort-controls">
            <span>Sort by:</span>
            <form method="GET" id="sortForm" style="display:inline;">
                <?php if(!empty($search)): ?>
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <?php endif; ?>
                <select name="sort_by" onchange="document.getElementById('sortForm').submit()">
                    <option value="id" <?php if($sort_by=='id') echo 'selected'; ?>>Default ID</option>
                    <option value="full_name" <?php if($sort_by=='full_name') echo 'selected'; ?>>Full Name</option>
                    <option value="student_id" <?php if($sort_by=='student_id') echo 'selected'; ?>>Student ID</option>
                    <option value="department" <?php if($sort_by=='department') echo 'selected'; ?>>Department</option>
                    <option value="semester" <?php if($sort_by=='semester') echo 'selected'; ?>>Semester</option>
                </select>
                <select name="order" onchange="document.getElementById('sortForm').submit()">
                    <option value="ASC" <?php if($order=='ASC') echo 'selected'; ?>>Ascending (A-Z / 1-9)</option>
                    <option value="DESC" <?php if($order=='DESC') echo 'selected'; ?>>Descending (Z-A / 9-1)'); ?></option>
                </select>
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Student ID</th>
                <th>Full Name</th>
                <th>Department</th>
                <th>Semester</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['department']); ?></td>
                    <td><?php echo htmlspecialchars($row['semester']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td>
                        <a href="edit-student.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                        <a href="database.php?delete=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="no-records">No student records found matching your criteria.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>