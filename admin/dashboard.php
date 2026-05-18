<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_role('admin');

$summary = fetch_one('SELECT
    SUM(CASE WHEN status = "Open" THEN 1 ELSE 0 END) AS open_complaints,
    SUM(CASE WHEN status = "Escalated" THEN 1 ELSE 0 END) AS escalated_complaints,
    SUM(CASE WHEN status = "Resolved" THEN 1 ELSE 0 END) AS resolved_complaints
FROM complaints');
$event_summary = fetch_one('SELECT
    COUNT(*) AS total_events,
    SUM(seats_total - seats_taken) AS seats_remaining
FROM events');
$fyp_summary = fetch_one('SELECT
    SUM(CASE WHEN review_status = "Pending" THEN 1 ELSE 0 END) AS pending_reviews,
    SUM(CASE WHEN review_status = "Needs Revision" THEN 1 ELSE 0 END) AS needs_revision
FROM fyp_groups');
$announcements = fetch_all('SELECT * FROM announcements WHERE is_active = 1 ORDER BY published_at DESC LIMIT 5');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="page-shell">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include __DIR__ . '/../includes/header.php'; ?>
        <div class="container">
            <div class="dashboard-grid">
                <div class="card widget">
                    <h3>Open Complaints</h3>
                    <strong><?php echo intval($summary['open_complaints']); ?></strong>
                </div>
                <div class="card widget">
                    <h3>Escalated Issues</h3>
                    <strong><?php echo intval($summary['escalated_complaints']); ?></strong>
                </div>
                <div class="card widget">
                    <h3>Pending FYP Reviews</h3>
                    <strong><?php echo intval($fyp_summary['pending_reviews']); ?></strong>
                </div>
                <div class="card widget">
                    <h3>Event Seats Remaining</h3>
                    <strong><?php echo intval($event_summary['seats_remaining']); ?></strong>
                </div>
            </div>
            <div class="card">
                <h3>Campus Announcements</h3>
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
