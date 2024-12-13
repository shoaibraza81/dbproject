<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FAST University</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- FontAwesome for icons -->
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f4f4f4;
        }

        /* Sidebar Styling */
        .sidebar {
            background-color: #343a40;
            color: white;
            width: 250px;
            min-height: 100vh;
            padding: 20px;
            position: fixed;
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            border-bottom: 2px solid #ffc107;
            padding-bottom: 10px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 15px;
            margin: 5px 0;
            border-radius: 5px;
            transition: background 0.3s;
            font-size: 18px;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .sidebar a.active {
            background-color: #ffc107;
            color: black;
        }

        .logout-btn {
            margin-top: auto;
            background-color: #dc3545;
            text-align: center;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        /* Main Content Styling */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
        }

        .header {
            background-color: #007BFF;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 32px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .card i {
            font-size: 40px;
            margin-bottom: 15px;
            color: #007BFF;
        }

        .card h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .card a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }

        .card a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <a href="#" class="active"><i class="fas fa-home"></i> Home</a>
        <a href="../pages/students.php"><i class="fas fa-user-graduate"></i> Manage Students</a>
        <a href="../pages/bus.php"><i class="fas fa-bus"></i> Manage Buses</a>
        <a href="../pages/routes.php"><i class="fas fa-route"></i> Manage Routes</a>
        <a href="../pages/driver.php"><i class="fas fa-users"></i> Manage Drivers</a>
        <a href="../pages/teacher.php"><i class="fas fa-user-tie"></i> Manage Teachers</a>
        <a href="../login.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Welcome, Admin</h1>
        </div>

        <!-- Dashboard Cards -->
        <div class="cards">
            <div class="card">
                <i class="fas fa-user-graduate"></i>
                <h3>Students</h3>
                <p>View and manage student records</p>
                <a href="../pages/students.php">Go to Students</a>
            </div>

            <div class="card">
                <i class="fas fa-bus"></i>
                <h3>Buses</h3>
                <p>View and manage buses</p>
                <a href="../pages/bus.php">Go to Buses</a>
            </div>

            <div class="card">
                <i class="fas fa-route"></i>
                <h3>Routes</h3>
                <p>View and manage routes</p>
                <a href="../pages/routes.php">Go to Routes</a>
            </div>

            <div class="card">
                <i class="fas fa-users"></i>
                <h3>Drivers</h3>
                <p>View and manage drivers</p>
                <a href="../pages/driver.php">Go to Drivers</a>
            </div>

            <div class="card">
                <i class="fas fa-user-tie"></i>
                <h3>Teachers</h3>
                <p>View and manage teachers</p>
                <a href="../pages/teacher.php">Go to Teachers</a>
            </div>
        </div>
    </div>

</body>
</html>