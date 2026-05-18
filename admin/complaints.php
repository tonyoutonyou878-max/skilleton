<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_role('admin');
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['complaint_id'])) {
    $status = $_POST['status'] ?? 'Open';
    $response = trim($_POST['admin_response'] ?? '');
    execute_query('UPDATE complaints SET status = ?, admin_response = ? WHERE id = ?', [$status, $response, $_POST['complaint_id']]);
    $message = 'Complaint updated successfully.';
}
$complaints = fetch_all('SELECT c.*, u.name AS student_name, u.email AS student_email FROM complaints c JOIN users u ON c.student_id = u.id ORDER BY c.created_at DESC');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Complaint Management</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="page-shell">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include __DIR__ . '/../includes/header.php'; ?>
        <div class="container">
            <div class="card">
                <h3>Complaint Management</h3>
                <?php if ($message): ?>
                    <div class="alert alert-error"><?php echo sanitize($message); ?></div>
                <?php endif; ?>
                <table>
                    <thead>
                        <tr><th>#</th><th>Student</th><th>Subject</th><th>Category</th><th>Priority</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($complaints as $complaint): ?>
                        <tr>
                            <td><?php echo intval($complaint['id']); ?></td>
                            <td><?php echo sanitize($complaint['student_name']); ?><br><small><?php echo sanitize($complaint['student_email']); ?></small></td>
                            <td><?php echo sanitize($complaint['subject']); ?></td>
                            <td><?php echo sanitize($complaint['category']); ?></td>
                            <td><?php echo sanitize($complaint['priority']); ?></td>
                            <td><?php echo sanitize($complaint['status']); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="complaint_id" value="<?php echo intval($complaint['id']); ?>">
                                    <select name="status">
                                        <?php foreach (['Open','In Progress','Resolved','Escalated'] as $status): ?>
                                            <option value="<?php echo $status; ?>" <?php echo $complaint['status'] === $status ? 'selected' : ''; ?>><?php echo $status; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <textarea name="admin_response" rows="2" placeholder="Admin response"><?php echo sanitize($complaint['admin_response']); ?></textarea>
                                    <button type="submit" class="btn btn-secondary">Save</button>
                                </form>
                            </td>
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
