<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_role('admin');
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['group_id'])) {
    $review = $_POST['review_status'] ?? 'Pending';
    $milestone = $_POST['milestone_status'] ?? 'Not Started';
    execute_query('UPDATE fyp_groups SET review_status = ?, milestone_status = ? WHERE id = ?', [$review, $milestone, $_POST['group_id']]);
    $message = 'FYP group updated successfully.';
}
$groups = fetch_all('SELECT f.*, u.name AS student_name, u.email AS student_email FROM fyp_groups f JOIN users u ON f.student_id = u.id ORDER BY f.updated_at DESC');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin FYP Management</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="page-shell">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include __DIR__ . '/../includes/header.php'; ?>
        <div class="container">
            <div class="card">
                <h3>FYP Group Review</h3>
                <?php if ($message): ?>
                    <div class="alert alert-error"><?php echo sanitize($message); ?></div>
                <?php endif; ?>
                <table>
                    <thead>
                        <tr><th>#</th><th>Student</th><th>Project</th><th>Supervisor</th><th>Milestone</th><th>Review</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groups as $group): ?>
                        <tr>
                            <td><?php echo intval($group['id']); ?></td>
                            <td><?php echo sanitize($group['student_name']); ?><br><small><?php echo sanitize($group['student_email']); ?></small></td>
                            <td><?php echo sanitize($group['project_title']); ?></td>
                            <td><?php echo sanitize($group['supervisor']); ?></td>
                            <td><?php echo sanitize($group['milestone_status']); ?></td>
                            <td><?php echo sanitize($group['review_status']); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="group_id" value="<?php echo intval($group['id']); ?>">
                                    <select name="milestone_status">
                                        <?php foreach (['Not Started','In Progress','Completed'] as $status): ?>
                                            <option value="<?php echo $status; ?>" <?php echo $group['milestone_status'] === $status ? 'selected' : ''; ?>><?php echo $status; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <select name="review_status">
                                        <?php foreach (['Pending','Approved','Needs Revision'] as $status): ?>
                                            <option value="<?php echo $status; ?>" <?php echo $group['review_status'] === $status ? 'selected' : ''; ?>><?php echo $status; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-secondary" type="submit">Save</button>
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
