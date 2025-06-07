<?php
$host = 'localhost';
$port = '3307'; // NEW LINE
$dbname = 'this_time_africa';
$username = 'root'; // change if different
$password = '';     // change if set

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    // Set error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
