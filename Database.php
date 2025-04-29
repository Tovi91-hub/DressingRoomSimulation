<?php
$host = 'localhost';
$dbname = 'dressing_simulation'; // ✅ NEW name
$username = 'dressingroom_user'; // ✅ user must have rights to this DB
$password = 'dress123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("DressingRoomSim DB connection failed: " . $e->getMessage());
    die("Database connection error. Please contact support.");
}