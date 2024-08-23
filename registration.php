<?php
include('dbconnect.php');
include('navbar.php');

// Initialize an array to store errors
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fullname = trim($_POST['fullname']);
    $gender = trim($_POST['gender']);
    $mobile = trim($_POST['mobile']);
    $role = trim($_POST['role']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate form data
    if (empty($fullname)) {
        $errors[] = "Full name is required.";
    }

    if (empty($gender)) {
        $errors[] = "Gender is required.";
    }

    if (empty($mobile)) {
        $errors[] = "Mobile number is required.";
    } elseif (!preg_match('/^[0-9]{10}$/', $mobile)) {
        $errors[] = "Mobile number must be 10 digits.";
    }

    if (empty($role)) {
        $errors[] = "Role is required.";
    }

    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if (empty($confirm_password)) {
        $errors[] = "Confirm password is required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if there are no errors before inserting into the database
    if (empty($errors)) {
        // Check if the user already exists
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $errors[] = "User already exists!";
        } else {
            // Insert new user into database
            $password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
            $sql = "INSERT INTO users (fullname, gender, mobile, role, username, email, password) 
                    VALUES ('$fullname', '$gender', '$mobile', '$role', '$username', '$email', '$password')";

            if ($conn->query($sql) === TRUE) {
                header('Location: login.php'); // Redirect to login page after successful registration
                exit;
            } else {
                $errors[] = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="css/registration.css">
</head>
<body>
    <div class="main-content">
        <h1>Register</h1>
        <!-- Registration form -->
        <form action="registration.php" method="post">
            <div>
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" value="<?= isset($fullname) ? htmlspecialchars($fullname) : ''; ?>">
            </div>
            <div>
                <label>Gender:</label>
                <div class="radio-group">
                    <input type="radio" id="male" name="gender" value="Male" required>
                    <label for="male">Male</label>

                    <input type="radio" id="female" name="gender" value="Female" required>
                    <label for="female">Female</label>

                    <input type="radio" id="other" name="gender" value="Other" required>
                    <label for="other">Other</label>
                </div>
            </div>
            <div>
                <label for="mobile">Mobile Number:</label>
                <input type="text" id="mobile" name="mobile" value="<?= isset($mobile) ? htmlspecialchars($mobile) : ''; ?>">
            </div>
            <div>
                <label for="role">Role:</label>
                <select id="role" name="role">
                    <option value="">Select Role</option>
                    <option value="Marketing Team" <?= isset($role) && $role == 'Marketing Team' ? 'selected' : ''; ?>>Marketing Team</option>
                    <option value="Director" <?= isset($role) && $role == 'Director' ? 'selected' : ''; ?>>Director</option>
                </select>
            </div>
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?= isset($username) ? htmlspecialchars($username) : ''; ?>">
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : ''; ?>">
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
            <div>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>
            <div>
                <button type="submit">Register</button>
            </div>
        </form>

        <!-- Display errors -->
        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p style='color: red;'>$error</p>";
            }
        }
        ?>
    </div>
</body>
</html>
