<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'Database.php';
$query = "SELECT * FROM simulation_logs WHERE user_id = ?";
$params = [$_SESSION['user_id']];

if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
    $query .= " AND DATE(created_at) BETWEEN ? AND ?";
    $params[] = $_GET['start_date'];
    $params[] = $_GET['end_date'];
}

$query .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$logs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simulation History</title>
    <style>
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
    <h2>Simulation History for <?= htmlspecialchars($_SESSION['username']) ?></h2>
    <a href="download_logs.php" style="margin-bottom: 15px; display: inline-block;">⬇ Download CSV</a>
    <form method="GET" action="logs.php" style="margin-top: 20px;">
    <label>From:
        <input type="date" name="start_date" value="<?= $_GET['start_date'] ?? '' ?>">
    </label>
    <label>To:
        <input type="date" name="end_date" value="<?= $_GET['end_date'] ?? '' ?>">
    </label>
    <button type="submit">Filter</button>
    </form>


    <?php if (count($logs) > 0): ?>

    <table>
        <tr>
            <th>Date</th>
            <th>Customers</th>
            <th>Rooms</th>
            <th>Items</th>
            <th>Total Time (s)</th>
            <th>Avg Items</th>
            <th>Avg Wait (s)</th>
            <th>Avg Usage (s)</th>
        </tr>
        <?php foreach ($logs as $log): ?>
        <tr>
            <td><?= $log['created_at'] ?></td>
            <td><?= $log['num_customers'] ?></td>
            <td><?= $log['num_rooms'] ?></td>
            <td><?= $log['num_random_items'] ?></td>
            <td><?= $log['total_time'] ?></td>
            <td><?= $log['avg_items'] ?></td>
            <td><?= $log['avg_wait'] ?></td>
            <td><?= $log['avg_usage'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
    <p>No simulation logs yet.</p>
    <?php endif; ?>

    <a href="dashboard.php">← Back to Dashboard</a>
</div>
</body>
</html>