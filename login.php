<?php
include('dbconnect.php');
include('navbar.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check user credentials
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id']; // Assuming we have 'id' field
            $_SESSION['user_role'] = $user['role']; // Store the user role in session
            header('Location: index.php'); // Redirect to dashboard
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="main-content">
        <h1>Login</h1>
        <form action="login.php" method="post">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit">Login</button>
            </div>
            <?php if (isset($error)): ?>
                <p style='color: red;'><?= $error ?></p>
            <?php endif; ?>
        </form>
         <div class="buttons-container" style="margin-top: 20px;">
                <a href="registration.php">
                    <button type="button">Register</button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
