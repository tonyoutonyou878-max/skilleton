<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_role('student');
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['event_id'])) {
    $event_id = $_POST['event_id'];
    $event = fetch_one('SELECT * FROM events WHERE id = ?', [$event_id]);
    $already = fetch_one('SELECT id FROM event_registrations WHERE event_id = ? AND student_id = ?', [$event_id, current_user_id()]);
    if ($event && $event['seats_taken'] < $event['seats_total'] && !$already) {
        execute_query('INSERT INTO event_registrations (event_id, student_id) VALUES (?, ?)', [$event_id, current_user_id()]);
        execute_query('UPDATE events SET seats_taken = seats_taken + 1 WHERE id = ?', [$event_id]);
        if ($event['seats_taken'] + 1 >= $event['seats_total']) {
            execute_query('UPDATE events SET status = "Full" WHERE id = ?', [$event_id]);
        }
        $message = 'Registered for the event successfully.';
    } elseif ($already) {
        $message = 'You are already registered for this event.';
    } else {
        $message = 'Event is full or not available.';
    }
}
$events = fetch_all('SELECT * FROM events ORDER BY event_date ASC');
$registrations = fetch_all('SELECT event_id FROM event_registrations WHERE student_id = ?', [current_user_id()]);
$registered = array_column($registrations, 'event_id');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Event Registration</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="page-shell">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include __DIR__ . '/../includes/header.php'; ?>
        <div class="container">
            <div class="card">
                <h3>Available Events</h3>
                <?php if ($message): ?><div class="alert alert-error"><?php echo sanitize($message); ?></div><?php endif; ?>
                <table>
                    <thead><tr><th>Title</th><th>Date</th><th>Location</th><th>Seats</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo sanitize($event['title']); ?></td>
                            <td><?php echo sanitize($event['event_date']); ?></td>
                            <td><?php echo sanitize($event['location']); ?></td>
                            <td><?php echo intval($event['seats_taken']) . '/' . intval($event['seats_total']); ?></td>
                            <td><?php echo sanitize($event['status']); ?></td>
                            <td>
                                <?php if (in_array($event['id'], $registered)): ?>
                                    Registered
                                <?php elseif ($event['seats_taken'] >= $event['seats_total']): ?>
                                    Full
                                <?php else: ?>
                                    <form method="POST"><input type="hidden" name="event_id" value="<?php echo intval($event['id']); ?>"><button class="btn btn-primary" type="submit">Register</button></form>
                                <?php endif; ?>
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
