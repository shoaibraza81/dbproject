<?php
include('../includes/db_connect.php');

// Handle form submission for adding a new driver
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_driver'])) {
    $d_name = mysqli_real_escape_string($conn, $_POST['name']);
    $d_phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $d_point_num = mysqli_real_escape_string($conn, $_POST['point_number']);
    $d_hiredate = mysqli_real_escape_string($conn, $_POST['hiredate']);
    
    if (!empty($d_name) && !empty($d_phone) && !empty($d_point_num) && !empty($d_hiredate)) {
        $query = "INSERT INTO driver (d_name, d_phone, d_point_num, d_hiredate) 
                  VALUES ('$d_name', '$d_phone', '$d_point_num', '$d_hiredate')";
        if (mysqli_query($conn, $query)) {
            header("Location: driver.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Handle deleting a driver
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM driver WHERE d_id = $id");
    header("Location: driver.php");
    exit();
}

// Handle updating a driver
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_driver'])) {
    $id = intval($_POST['d_id']);
    $d_name = mysqli_real_escape_string($conn, $_POST['name']);
    $d_phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $d_point_num = mysqli_real_escape_string($conn, $_POST['point_number']);
    $d_hiredate = mysqli_real_escape_string($conn, $_POST['hiredate']);

    $query = "UPDATE driver SET 
              d_name = '$d_name', 
              d_phone = '$d_phone', 
              d_point_num = '$d_point_num', 
              d_hiredate = '$d_hiredate' 
              WHERE d_id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: driver.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Handle search functionality
$search = '';
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM driver WHERE 
              d_name LIKE '%$search%' OR 
              d_phone LIKE '%$search%' OR 
              d_point_num LIKE '%$search%'";
} else {
    $query = "SELECT * FROM driver";
}
$result = mysqli_query($conn, $query);

// Fetch driver data for updating
$updateData = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $updateResult = mysqli_query($conn, "SELECT * FROM driver WHERE d_id = $id");
    $updateData = mysqli_fetch_assoc($updateResult);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Drivers</title>
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

        .form-container {
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
    </style>
</head>
<body>

    <div class="header">
        <img src="../images/logo.png" alt="Logo">
        <h1>Manage Drivers</h1>
    </div>

    <div class="form-container">
        <form method="POST" action="driver.php">
            <?php if (isset($updateData)): ?>
                <input type="hidden" name="d_id" value="<?php echo $updateData['d_id']; ?>">
            <?php endif; ?>
            <input type="text" name="name" placeholder="Name" value="<?php echo $updateData['d_name'] ?? ''; ?>" required>
            <input type="text" name="phone" placeholder="Phone Number" value="<?php echo $updateData['d_phone'] ?? ''; ?>" required>
            <input type="text" name="point_number" placeholder="Point Number" value="<?php echo $updateData['d_point_num'] ?? ''; ?>" required>
            <input type="text" name="hiredate" placeholder="Hire Date (YYYY-MM-DD)" value="<?php echo $updateData['d_hiredate'] ?? ''; ?>" required>
            <button type="submit" name="<?php echo isset($updateData) ? 'update_driver' : 'add_driver'; ?>">
                <?php echo isset($updateData) ? 'Update Driver' : 'Add Driver'; ?>
            </button>
        </form>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Point Number</th>
            <th>Hire Date</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['d_id']; ?></td>
                <td><?php echo $row['d_name']; ?></td>
                <td><?php echo $row['d_phone']; ?></td>
                <td><?php echo $row['d_point_num']; ?></td>
                <td><?php echo $row['d_hiredate']; ?></td>
                <td>
                    <a href="driver.php?edit=<?php echo $row['d_id']; ?>" class="update-btn">Update</a>
                    <a href="driver.php?delete=<?php echo $row['d_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
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
