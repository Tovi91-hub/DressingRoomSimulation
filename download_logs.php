<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'Database.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="simulation_logs.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Date', 'Customers', 'Rooms', 'Items', 'Total Time (s)', 'Avg Items', 'Avg Wait (s)', 'Avg Usage (s)']);

$stmt = $pdo->prepare("SELECT * FROM simulation_logs WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$logs = $stmt->fetchAll();

foreach ($logs as $log) {
    fputcsv($output, [
        $log['created_at'],
        $log['num_customers'],
        $log['num_rooms'],
        $log['num_random_items'],
        $log['total_time'],
        $log['avg_items'],
        $log['avg_wait'],
        $log['avg_usage']
    ]);
}

fclose($output);
exit;