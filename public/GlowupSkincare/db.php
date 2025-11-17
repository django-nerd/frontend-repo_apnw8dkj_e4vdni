<?php
require_once __DIR__ . '/config.php';
session_start();
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    die('Gagal terhubung ke MySQL: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

function db(): mysqli {
    global $mysqli;
    return $mysqli;
}
?>
