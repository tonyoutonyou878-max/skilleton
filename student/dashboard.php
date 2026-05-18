<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_role('student');
$user = current_user();
$my_complaints = fetch_all('SELECT * FROM complaints WHERE student_id = ? ORDER BY created_at DESC', [current_user_id()]);
$my_registrations = fetch_all('SELECT e.* FROM events e JOIN event_registrations r ON r.event_id = e.id WHERE r.student_id = ? ORDER BY e.event_date ASC', [current_user_id()]);
$my_fyp = fetch_one('SELECT * FROM fyp_groups WHERE student_id = ?', [current_user_id()]);
$announcements = fetch_all('SELECT * FROM announcements WHERE is_active = 1 ORDER BY published_at DESC LIMIT 4');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="page-shell">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include __DIR__ . '/../includes/header.php'; ?>
        <div class="container">
            <div class="dashboard-grid">
                <div class="card widget"><h3>My Complaints</h3><strong><?php echo count($my_complaints); ?></strong></div>
                <div class="card widget"><h3>Events Registered</h3><strong><?php echo count($my_registrations); ?></strong></div>
                <div class="card widget"><h3>FYP Status</h3><strong><?php echo sanitize($my_fyp['review_status'] ?? 'Not Submitted'); ?></strong></div>
            </div>
            <div class="card">
                <h3>Latest Announcements</h3>
                <?php foreach ($announcements as $announcement): ?>
                    <div style="margin-bottom:16px;">
                        <strong><?php echo sanitize($announcement['title']); ?></strong>
                        <p><?php echo sanitize($announcement['message']); ?></p>
                        <small><?php echo date('M j, Y', strtotime($announcement['published_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php include __DIR__ . '/../includes/footer.php'; ?>
    </div>
</div>
<script src="../assets/js/app.js"></script>
</body>
</html>
