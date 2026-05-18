<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_role('admin');
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['event_id'])) {
    $status = $_POST['status'] ?? 'Open';
    execute_query('UPDATE events SET status = ? WHERE id = ?', [$status, $_POST['event_id']]);
    $message = 'Event status updated.';
}
$events = fetch_all('SELECT * FROM events ORDER BY event_date ASC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Event Management</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="page-shell">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include __DIR__ . '/../includes/header.php'; ?>
        <div class="container">
            <div class="card">
                <h3>Event Management</h3>
                <?php if ($message): ?>
                    <div class="alert alert-error"><?php echo sanitize($message); ?></div>
                <?php endif; ?>
                <table>
                    <thead>
                        <tr><th>#</th><th>Title</th><th>Date</th><th>Location</th><th>Seats</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo intval($event['id']); ?></td>
                            <td><?php echo sanitize($event['title']); ?></td>
                            <td><?php echo sanitize($event['event_date']); ?></td>
                            <td><?php echo sanitize($event['location']); ?></td>
                            <td><?php echo intval($event['seats_taken']) . '/' . intval($event['seats_total']); ?></td>
                            <td><?php echo sanitize($event['status']); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="event_id" value="<?php echo intval($event['id']); ?>">
                                    <select name="status">
                                        <?php foreach (['Open','Closed','Full'] as $status): ?>
                                            <option value="<?php echo $status; ?>" <?php echo $event['status'] === $status ? 'selected' : ''; ?>><?php echo $status; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-secondary" type="submit">Update</button>
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
