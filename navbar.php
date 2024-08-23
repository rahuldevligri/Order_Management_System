<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Order Dashboard</title>
    <link rel="stylesheet" href="css/navbar.css"> 
</head>
<body>
    <!-- Top Navbar -->
    <div class="top-navbar">
    <a href="index.php" class="right">Home</a>
    <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
            <a href="logout.php" class="right">Logout</a>
        <?php else: ?>
            <a href="login.php" class="right">Login</a>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="sales_order.php" class="sidebar-link">Sales Order</a>
        <a href="sales_order_status.php" class="sidebar-link">Sales Order Status</a>
    </div>
</body>
</html>
