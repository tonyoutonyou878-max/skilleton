<?php
require_once __DIR__ . '/../config.php';

function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

function current_user_role() {
    return $_SESSION['user_role'] ?? null;
}

function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function login_user($email, $password, &$error = null) {
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare('SELECT id, role, password_hash FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && hash_equals($user['password_hash'], hash('sha256', $password))) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            return true;
        }
        $error = 'Invalid email or password. Please try again.';
    } catch (PDOException $e) {
        $error = 'Database connection error. Please start MySQL and verify the database settings.';
    }
    return false;
}

function register_user($name, $email, $password, &$error = null) {
    try {
        $pdo = db_connect();
        $existing = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $existing->execute([$email]);
        if ($existing->fetch()) {
            $error = 'An account with this email already exists.';
            return false;
        }
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $email, hash('sha256', $password), 'student']);
        return true;
    } catch (PDOException $e) {
        $error = 'Unable to create account. Please contact the system administrator.';
    }
    return false;
}

function require_role($role) {
    if (!is_logged_in() || current_user_role() !== $role) {
        header('Location: ../index.php');
        exit;
    }
}

function require_auth() {
    if (!is_logged_in()) {
        header('Location: ../index.php');
        exit;
    }
}
