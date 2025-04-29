<!-- login.php -->
<?php session_start(); if (isset($_SESSION['user_id'])) header("Location: dashboard.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Dressing Room Sim</title>
</head>
<body>
    <h2>Login</h2>
    <form action="authenticate.php" method="POST">
        <label>Username: <input type="text" name="username" required></label><br><br>
        <label>Password: <input type="password" name="password" required></label><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>