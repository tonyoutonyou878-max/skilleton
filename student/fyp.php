<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_role('student');
$message = '';
$existing = fetch_one('SELECT * FROM fyp_groups WHERE student_id = ?', [current_user_id()]);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['project_title'] ?? '');
    $supervisor = trim($_POST['supervisor'] ?? '');
    $milestone = $_POST['milestone_status'] ?? 'Not Started';
    if ($existing) {
        execute_query('UPDATE fyp_groups SET project_title = ?, supervisor = ?, milestone_status = ? WHERE id = ?', [$title, $supervisor, $milestone, $existing['id']]);
        $message = 'FYP details updated successfully.';
    } else {
        execute_query('INSERT INTO fyp_groups (student_id, project_title, supervisor, milestone_status) VALUES (?, ?, ?, ?)', [current_user_id(), $title, $supervisor, $milestone]);
        $message = 'FYP project submitted successfully.';
    }
    $existing = fetch_one('SELECT * FROM fyp_groups WHERE student_id = ?', [current_user_id()]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student FYP Tracking</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="page-shell">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include __DIR__ . '/../includes/header.php'; ?>
        <div class="container">
            <div class="card">
                <h3>FYP Project Submission</h3>
                <?php if ($message): ?><div class="alert alert-error"><?php echo sanitize($message); ?></div><?php endif; ?>
                <form method="POST">
                    <label>Project Title</label>
                    <input type="text" name="project_title" required value="<?php echo sanitize($existing['project_title'] ?? ''); ?>">
                    <label>Supervisor</label>
                    <input type="text" name="supervisor" required value="<?php echo sanitize($existing['supervisor'] ?? ''); ?>">
                    <label>Milestone Status</label>
                    <select name="milestone_status">
                        <?php foreach (['Not Started','In Progress','Completed'] as $status): ?>
                            <option value="<?php echo $status; ?>" <?php echo ($existing['milestone_status'] ?? '') === $status ? 'selected' : ''; ?>><?php echo $status; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-primary" type="submit"><?php echo $existing ? 'Update Project' : 'Submit Project'; ?></button>
                </form>
            </div>
            <?php if ($existing): ?>
            <div class="card">
                <h3>My FYP Status</h3>
                <p><strong>Title:</strong> <?php echo sanitize($existing['project_title']); ?></p>
                <p><strong>Supervisor:</strong> <?php echo sanitize($existing['supervisor']); ?></p>
                <p><strong>Milestone:</strong> <?php echo sanitize($existing['milestone_status']); ?></p>
                <p><strong>Review Status:</strong> <?php echo sanitize($existing['review_status']); ?></p>
            </div>
            <?php endif; ?>
        </div>
        <?php include __DIR__ . '/../includes/footer.php'; ?>
    </div>
</div>
<script src="../assets/js/app.js"></script>
</body>
</html>
