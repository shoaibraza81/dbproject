<?php
session_start();

// Check if the user is logged in and is a student
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

include('../includes/db_connect.php');

// Fetch the username from the session
$username = $_SESSION['username'];

// Query to fetch student information based on the logged-in username
$query = "SELECT * FROM student WHERE s_id = (SELECT s_id FROM users WHERE username = '$username')";
$result = mysqli_query($conn, $query);

// Check if the query is successful and retrieve the student's data
if ($result) {
    $student = mysqli_fetch_assoc($result);
} else {
    die("Error fetching student data: " . mysqli_error($conn));
}

// Fetch bus route details assigned to this student
$bus_id = $student['s_point_num'];
$route_query = "SELECT * FROM route WHERE bus_bus_id = '$bus_id'";
$route_result = mysqli_query($conn, $route_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - FAST University</title>
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

        .sidebar img {
            width: 100%;
            max-width: 150px;
            margin-bottom: 30px;
            display: block;
            margin: 0 auto;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            border-bottom: 2px solid #17a2b8;
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
            background-color: #17a2b8;
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
            background-color: #17a2b8;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 32px;
        }

        .student-info, .bus-info {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .student-info p, .bus-info p {
            font-size: 18px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #17a2b8;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="../images/logo.png" alt="Logo">
        <h2>Student Dashboard</h2>
        <a href="student_dashboard.php" class="active"><i class="fas fa-home"></i> Home</a>
        <a href="search_driver.php"><i class="fas fa-search"></i> Search Driver</a>
        <a href="search_point.php"><i class="fas fa-search"></i> Search Point Number by Stop</a>
        <a href="../login.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Welcome, <?php echo htmlspecialchars($student['s_name']); ?></h1>
        </div>

        <!-- Student Information Section -->
        <div class="student-info">
            <h3>Your Information</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($student['s_name']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['s_phone']); ?></p>
            <p><strong>Department:</strong> <?php echo htmlspecialchars($student['s_dept']); ?></p>
            <p><strong>Semester:</strong> <?php echo htmlspecialchars($student['s_semester']); ?></p>
            <p><strong>Point Number:</strong> <?php echo htmlspecialchars($student['s_point_num']); ?></p>
            <p><strong>Stop:</strong> <?php echo htmlspecialchars($student['s_stop']); ?></p>
        </div>

        <!-- Bus Information Section -->
        <div class="bus-info">
            <h3>Your Bus Route</h3>
            <table>
                <tr>
                    <th>Route Point</th>
                    <th>Stop</th>
                    <th>Time</th>
                </tr>
                <?php while ($route = mysqli_fetch_assoc($route_result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($route['r_point_num']); ?></td>
                        <td><?php echo htmlspecialchars($route['r_stop']); ?></td>
                        <td><?php echo htmlspecialchars($route['r_time']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

    </div>

</body>
</html>

<?php mysqli_close($conn); ?>

