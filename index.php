<?php
include('navbar.php');

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    // User is not logged in, redirect to login page
    header('Location: login.php');
    exit;
}
// Retrieve the user's role from the session or default to 'guest'
$role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'guest';
?>

<div class="main-content">
    <h1>Welcome to the Sales Order Dashboard</h1>
    <p>Your role: <?= htmlspecialchars($role) ?></p>
</div>
