<?php
// Database & app constants
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'cusit_portal');
define('DB_USER', 'root');
define('DB_PASS', '');

define('APP_NAME', 'CUSIT Smart Campus Portal');

define('BASE_URL', '/SmartCampusPortal');

function db_connect() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            throw new PDOException('Unable to connect to the database. Please make sure MySQL is running and the database "' . DB_NAME . '" exists. ' . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }
    return $pdo;
}
