<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <p>Log in successful.</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>