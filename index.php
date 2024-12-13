<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Fast University Point Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 50px;
        }
        h1 {
            color: #333;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 20px;
        }
        nav ul li a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        nav ul li a:hover {
            text-decoration: underline;
        }
        a.logout {
            display: inline-block;
            margin-top: 20px;
            background-color: #dc3545;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
        }
        a.logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <h1>Welcome to Fast University Point Management System, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    
    <nav>
        <ul>
            <li><a href="pages/students.php">Manage Students</a></li>
            <li><a href="pages/routes.php">Manage Routes</a></li>
            <li><a href="pages/buses.php">Manage Buses</a></li>
            <li><a href="pages/drivers.php">Manage Drivers</a></li>
            <li><a href="pages/teachers.php">Manage Teachers</a></li>
        </ul>
    </nav>

    <a href="logout.php" class="logout">Logout</a>
</body>
</html>
