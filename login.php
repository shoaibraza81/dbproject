<?php
session_start();
include 'includes/db_connect.php';

// Get the role from the URL
$role = isset($_GET['role']) ? $_GET['role'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check login credentials based on role
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password' AND role='$role'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: dashboards/admin_dashboard.php');
        } else {
            header('Location: dashboards/student_dashboard.php');
        }
        exit();
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - FAST University</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            background-image: url('images/background.jpg'); /* Replace with the correct path */
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .login-container {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.7);
            width: 300px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 36px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .role-title {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: none;
            border-radius: 4px;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-bottom: 10px;
        }

        button:hover {
            background-color: #218838;
        }

        .go-back-btn {
            display: block;
            background-color: #ffc107;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            text-align: center;
        }

        .go-back-btn:hover {
            background-color: #e0a800;
        }

        p {
            color: red;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>FAST UNIVERSITY</h2>
        <div class="role-title">
            <?php echo $role === 'admin' ? 'Admin Login' : 'Student Login'; ?>
        </div>
        <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
        <form method="POST" action="login.php?role=<?php echo $role; ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
        </form>
        
        <a href="role_selection.php" class="go-back-btn">Go Back</a>
    </div>
</body>
</html>
