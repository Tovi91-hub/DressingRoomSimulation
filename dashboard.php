<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dressing Room Simulation - Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f2f2f2;
        }
        h2 {
            color: #333;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 350px;
        }
        input {
            padding: 8px;
            width: 100%;
            margin: 10px 0;
        }
        button {
            padding: 10px 15px;
            background-color: #2e86de;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .logout {
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
<p>Enter the simulation parameters below:</p>

<form action="simulate.php" method="POST">
    <label>Number of Customers:</label>
    <input type="number" name="customers" min="1" required>

    <label>Number of Dressing Rooms:</label>
    <input type="number" name="rooms" min="1" required>

    <label>Number of Clothing Items (0 for random):</label>
    <input type="number" name="items" min="0" max="20" required>

    <button type="submit">Run Simulation</button>
</form>

<a class="logout" href="logout.php">Logout</a>
<br><br>
<a href="logs.php">View Simulation History</a>


</body>
</html>