<?php
require_once __DIR__ . '/../config.php';

function fetch_all($query, $params = []) {
    $stmt = db_connect()->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function fetch_one($query, $params = []) {
    $stmt = db_connect()->prepare($query);
    $stmt->execute($params);
    return $stmt->fetch();
}

function execute_query($query, $params = []) {
    $stmt = db_connect()->prepare($query);
    return $stmt->execute($params);
}

function sanitize($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function current_user() {
    if (!is_logged_in()) {
        return null;
    }
    return fetch_one('SELECT id, name, email, role FROM users WHERE id = ?', [current_user_id()]);
}
