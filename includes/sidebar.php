<?php
require_once __DIR__ . '/auth.php';
$current = basename($_SERVER['PHP_SELF']);
$role = current_user_role();
$items = [];
if ($role === 'admin') {
    $items = [
        ['label' => 'Dashboard', 'href' => 'dashboard.php'],
        ['label' => 'Complaints', 'href' => 'complaints.php'],
        ['label' => 'Events', 'href' => 'events.php'],
        ['label' => 'FYP', 'href' => 'fyp.php'],
    ];
} else {
    $items = [
        ['label' => 'Dashboard', 'href' => 'dashboard.php'],
        ['label' => 'Complaints', 'href' => 'complaints.php'],
        ['label' => 'Events', 'href' => 'events.php'],
        ['label' => 'FYP', 'href' => 'fyp.php'],
    ];
}
?>
<aside class="sidebar">
    <h2>Navigation</h2>
    <?php foreach ($items as $item): ?>
        <a href="<?php echo $item['href']; ?>" class="<?php echo $current === $item['href'] ? 'active' : ''; ?>"><?php echo $item['label']; ?></a>
    <?php endforeach; ?>
</aside>
