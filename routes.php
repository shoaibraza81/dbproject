<?php
include('../includes/db_connect.php');

// Handle form submission for adding a new route
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_route'])) {
    $r_stop = mysqli_real_escape_string($conn, $_POST['stop']);
    $r_point_num = mysqli_real_escape_string($conn, $_POST['point_number']);
    $r_time = mysqli_real_escape_string($conn, $_POST['time']);

    if (!empty($r_stop) && !empty($r_point_num) && !empty($r_time)) {
        // Insert query to add a new route
        $query = "INSERT INTO route (r_stop, r_point_num, r_time) 
                  VALUES ('$r_stop', '$r_point_num', '$r_time')";
        if (mysqli_query($conn, $query)) {
            header("Location: routes.php");  // Redirect to routes.php after successful insertion
            exit();
        } else {
            echo "Error: " . mysqli_error($conn); // Error handling
        }
    }
}

// Handle deleting a route
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if (mysqli_query($conn, "DELETE FROM route WHERE r_point_num = $id")) {
        header("Location: routes.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn); // Error handling
    }
}

// Handle updating a route
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_route'])) {
    $id = intval($_POST['r_point_num']);
    $r_stop = mysqli_real_escape_string($conn, $_POST['stop']);
    $r_point_num = mysqli_real_escape_string($conn, $_POST['point_number']);
    $r_time = mysqli_real_escape_string($conn, $_POST['time']);

    $query = "UPDATE route SET 
              r_stop = '$r_stop', 
              r_point_num = '$r_point_num', 
              r_time = '$r_time' 
              WHERE r_point_num = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: routes.php");  // Redirect to routes.php after successful update
        exit();
    } else {
        echo "Error: " . mysqli_error($conn); // Error handling
    }
}

// Handle search functionality
$search = '';
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM route WHERE 
              r_stop LIKE '%$search%' OR 
              r_point_num LIKE '%$search%' OR 
              r_time LIKE '%$search%'";
} else {
    $query = "SELECT * FROM route";  // Default query
}
$result = mysqli_query($conn, $query);

// Fetch route data for updating
$updateData = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $updateResult = mysqli_query($conn, "SELECT * FROM route WHERE r_point_num = $id");
    $updateData = mysqli_fetch_assoc($updateResult);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Routes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            background-color: #007BFF;
            padding: 20px;
            border-radius: 10px;
            color: white;
        }

        .header img {
            height: 50px;
            margin-right: 20px;
        }

        h1 {
            margin: 0;
        }

        .form-container, .search-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            margin: 20px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input[type="text"], button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            background-color: #fff;
            color: #333;
            margin-top: 20px;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a.delete-btn, a.update-btn {
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }

        a.delete-btn {
            background-color: #dc3545;
        }

        a.update-btn {
            background-color: #ffc107;
            color: black;
        }

        .go-back-btn {
            text-align: center;
            margin-top: 20px;
        }

        .go-back-btn a {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="../images/logo.png" alt="Logo">
        <h1>Manage Routes</h1>
    </div>

    <div class="form-container">
        <h3><?php echo isset($updateData) ? 'Update Route' : 'Add New Route'; ?></h3>
        <form method="POST" action="routes.php">
            <?php if (isset($updateData)): ?>
                <input type="hidden" name="r_point_num" value="<?php echo $updateData['r_point_num']; ?>">
            <?php endif; ?>
            <input type="text" name="stop" placeholder="Stop" value="<?php echo $updateData['r_stop'] ?? ''; ?>" required>
            <input type="text" name="point_number" placeholder="Point Number" value="<?php echo $updateData['r_point_num'] ?? ''; ?>" required>
            <input type="text" name="time" placeholder="Route Time" value="<?php echo $updateData['r_time'] ?? ''; ?>" required>
            <button type="submit" name="<?php echo isset($updateData) ? 'update_route' : 'add_route'; ?>">
                <?php echo isset($updateData) ? 'Update Route' : 'Add Route'; ?>
            </button>
        </form>
    </div>

    <div class="search-container">
        <h3>Search Routes</h3>
        <form method="GET" action="routes.php">
            <input type="text" name="search" placeholder="Search by Stop, Point Number, or Time" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <table>
        <tr>
            <th>Point Number</th>
            <th>Stop</th>
            <th>Time</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['r_point_num']; ?></td>
                <td><?php echo $row['r_stop']; ?></td>
                <td><?php echo $row['r_time']; ?></td>
                <td>
                    <a href="routes.php?edit=<?php echo $row['r_point_num']; ?>" class="update-btn">Update</a>
                    <a href="routes.php?delete=<?php echo $row['r_point_num']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="go-back-btn">
        <a href="javascript:history.back()">Go Back</a>
    </div>

</body>
</html>

<?php mysqli_close($conn); ?>
