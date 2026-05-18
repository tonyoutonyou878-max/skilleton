<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_role('student');
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $category = trim($_POST['category'] ?? 'General');
    $priority = $_POST['priority'] ?? 'Medium';
    $details = trim($_POST['details'] ?? '');
    $existing = fetch_one('SELECT id FROM complaints WHERE student_id = ? AND subject = ? AND status != "Resolved"', [current_user_id(), $subject]);
    if ($existing) {
        $message = 'You already have an active complaint with this subject.';
    } else {
        execute_query('INSERT INTO complaints (student_id, category, priority, subject, details) VALUES (?, ?, ?, ?, ?)', [current_user_id(), $category, $priority, $subject, $details]);
        $message = 'Complaint submitted successfully.';
    }
}
$complaints = fetch_all('SELECT * FROM complaints WHERE student_id = ? ORDER BY created_at DESC', [current_user_id()]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Complaints</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="page-shell">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include __DIR__ . '/../includes/header.php'; ?>
        <div class="container">
            <div class="card">
                <h3>Submit a Complaint</h3>
                <?php if ($message): ?><div class="alert alert-error"><?php echo sanitize($message); ?></div><?php endif; ?>
                <form method="POST">
                    <label>Subject</label>
                    <input type="text" name="subject" required>
                    <label>Category</label>
                    <select name="category">
                        <option>Facilities</option>
                        <option>WiFi</option>
                        <option>Academic</option>
                        <option>Administration</option>
                        <option>Other</option>
                    </select>
                    <label>Priority</label>
                    <select name="priority">
                        <option>Low</option>
                        <option selected>Medium</option>
                        <option>High</option>
                    </select>
                    <label>Details</label>
                    <textarea name="details" rows="5" required></textarea>
                    <button class="btn btn-primary" type="submit">Submit Complaint</button>
                </form>
            </div>
            <div class="card">
                <h3>My Complaints</h3>
                <table>
                    <thead><tr><th>Subject</th><th>Status</th><th>Priority</th><th>Response</th></tr></thead>
                    <tbody>
                        <?php foreach ($complaints as $complaint): ?>
                            <tr>
                                <td><?php echo sanitize($complaint['subject']); ?></td>
                                <td><?php echo sanitize($complaint['status']); ?></td>
                                <td><?php echo sanitize($complaint['priority']); ?></td>
                                <td><?php echo sanitize($complaint['admin_response'] ?: 'Pending'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php include __DIR__ . '/../includes/footer.php'; ?>
    </div>
</div>
<script src="../assets/js/app.js"></script>
</body>
</html>
