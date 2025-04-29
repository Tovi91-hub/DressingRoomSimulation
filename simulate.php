<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numCustomers = (int)$_POST['customers'];
    $numRooms = (int)$_POST['rooms'];
    $itemSetting = (int)$_POST['items'];

    $roomsAvailable = $numRooms;
    $roomQueue = [];
    $customers = [];

    $startTime = microtime(true);

    for ($i = 1; $i <= $numCustomers; $i++) {
        // Determine number of items
        $items = ($itemSetting === 0) ? rand(1, 6) : min($itemSetting, 20);
        $customers[] = [
            'id' => $i,
            'items' => $items,
            'arrival_time' => microtime(true),
        ];
    }

    $totalUsageTime = 0;
    $totalWaitTime = 0;

    foreach ($customers as $i => &$cust) {
        $waitStart = microtime(true);

        // Simulate waiting for room
        while ($roomsAvailable === 0) {
            usleep(rand(100000, 300000)); // 0.1 sec wait before checking again
        }

        // Got a room
        $roomsAvailable--;
        $waitEnd = microtime(true);
        $cust['wait_time'] = $waitEnd - $waitStart;
        $totalWaitTime += $cust['wait_time'];

        // Simulate trying on items
        $cust['try_start'] = microtime(true);
        for ($j = 0; $j < $cust['items']; $j++) {
            sleep(rand(1, 3)); // 1 to 3 seconds per item
        }
        $cust['try_end'] = microtime(true);
        $cust['use_time'] = $cust['try_end'] - $cust['try_start'];
        $totalUsageTime += $cust['use_time'];

        $roomsAvailable++;
    }

    $endTime = microtime(true);
    $totalElapsed = $endTime - $startTime;
    $avgItems = array_sum(array_column($customers, 'items')) / $numCustomers;
    $avgUse = $totalUsageTime / $numCustomers;
    $avgWait = $totalWaitTime / $numCustomers;
}
require 'Database.php';

try {
    $stmt = $pdo->prepare("
        INSERT INTO simulation_logs
        (user_id, num_customers, num_rooms, num_random_items, total_time, avg_items, avg_wait, avg_usage)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $_SESSION['user_id'],
        $numCustomers,
        $numRooms,
        $itemSetting,
        round($totalElapsed, 2),
        round($avgItems, 2),
        round($avgWait, 2),
        round($avgUse, 2)
    ]);
} catch (PDOException $e) {
    echo "Error logging simulation: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simulation Results</title>
    <style>
      /*  body { font-family: Arial, sans-serif; margin: 30px; }
        h2 { color: #2e86de; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #f4f4f4; }
        .back { margin-top: 20px; display: inline-block; } */
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 40px;
            background: #f1f1f1;
            color: #333;
        }

        h2 {
            color: #2e86de;
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 30px;
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 15px 12px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f4f8fb;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f0f8ff;
        }

        a {
            margin-top: 25px;
            display: inline-block;
            color: #2e86de;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Simulation Results</h2>

    <p><strong>Total Customers:</strong> <?= $numCustomers ?></p>
    <p><strong>Total Dressing Rooms:</strong> <?= $numRooms ?></p>
    <p><strong>Total Time Elapsed:</strong> <?= round($totalElapsed, 2) ?> seconds</p>
    <p><strong>Average Items per Customer:</strong> <?= round($avgItems, 2) ?></p>
    <p><strong>Average Room Usage Time:</strong> <?= round($avgUse, 2) ?> seconds</p>
    <p><strong>Average Wait Time:</strong> <?= round($avgWait, 2) ?> seconds</p>

    <table>
        <tr>
            <th>Customer ID</th>
            <th>Items</th>
            <th>Wait Time (sec)</th>
            <th>Usage Time (sec)</th>
        </tr>
        <?php foreach ($customers as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= $c['items'] ?></td>
            <td><?= round($c['wait_time'], 2) ?></td>
            <td><?= round($c['use_time'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a class="back" href="dashboard.php">← Back to Dashboard</a>
</div>
</body>
</html>