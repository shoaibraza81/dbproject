<?php
include('../includes/db_connect.php');

// Handle form submission for adding a new bus
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_bus'])) {
    $b_point_num = mysqli_real_escape_string($conn, $_POST['point_number']);
    $b_type = mysqli_real_escape_string($conn, $_POST['type']);
    $b_capacity = mysqli_real_escape_string($conn, $_POST['capacity']);
    $b_track_id = mysqli_real_escape_string($conn, $_POST['track_id']);

    if (!empty($b_point_num) && !empty($b_type) && !empty($b_capacity) && !empty($b_track_id)) {
        $query = "INSERT INTO bus (b_point_num, b_type, b_capacity, b_track_id) 
                  VALUES ('$b_point_num', '$b_type', '$b_capacity', '$b_track_id')";
        mysqli_query($conn, $query);
        header("Location: bus.php");
        exit();
    }
}

// Handle deleting a bus
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM bus WHERE bus_id = $id");
    header("Location: bus.php");
    exit();
}

// Handle updating a bus
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_bus'])) {
    $id = intval($_POST['bus_id']);
    $b_point_num = mysqli_real_escape_string($conn, $_POST['point_number']);
    $b_type = mysqli_real_escape_string($conn, $_POST['type']);
    $b_capacity = mysqli_real_escape_string($conn, $_POST['capacity']);
    $b_track_id = mysqli_real_escape_string($conn, $_POST['track_id']);

    $query = "UPDATE bus SET 
              b_point_num = '$b_point_num', 
              b_type = '$b_type', 
              b_capacity = '$b_capacity', 
              b_track_id = '$b_track_id' 
              WHERE bus_id = $id";
    mysqli_query($conn, $query);
    header("Location: bus.php");
    exit();
}

// Handle search functionality (only search by point number)
$search = '';
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM bus WHERE b_point_num LIKE '%$search%'";
} else {
    $query = "SELECT * FROM bus";
}
$result = mysqli_query($conn, $query);

// Fetch bus data for updating
$updateData = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $updateResult = mysqli_query($conn, "SELECT * FROM bus WHERE bus_id = $id");
    $updateData = mysqli_fetch_assoc($updateResult);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bus</title>
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

        input[type="text"], button, select {
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
        <h1>Manage Bus</h1>
    </div>

    <div class="form-container">
        <h3><?php echo isset($updateData) ? 'Update Bus' : 'Add New Bus'; ?></h3>
        <form method="POST" action="bus.php">
            <?php if (isset($updateData)): ?>
                <input type="hidden" name="bus_id" value="<?php echo $updateData['bus_id']; ?>">
            <?php endif; ?>
            <input type="text" name="point_number" placeholder="Point Number" value="<?php echo $updateData['b_point_num'] ?? ''; ?>" required>
            <input type="text" name="type" placeholder="Bus Type" value="<?php echo $updateData['b_type'] ?? ''; ?>" required>
            <input type="text" name="capacity" placeholder="Bus Capacity" value="<?php echo $updateData['b_capacity'] ?? ''; ?>" required>
            <input type="text" name="track_id" placeholder="Track ID" value="<?php echo $updateData['b_track_id'] ?? ''; ?>" required>
            <button type="submit" name="<?php echo isset($updateData) ? 'update_bus' : 'add_bus'; ?>">
                <?php echo isset($updateData) ? 'Update Bus' : 'Add Bus'; ?>
            </button>
        </form>
    </div>

    <div class="search-container">
        <h3>Search Buses</h3>
        <form method="GET" action="bus.php">
            <input type="text" name="search" placeholder="Search by Point Number" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <table>
        <tr>
            <th>Point Number</th>
            <th>Type</th>
            <th>Capacity</th>
            <th>Track ID</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['b_point_num']; ?></td>
                <td><?php echo $row['b_type']; ?></td>
                <td><?php echo $row['b_capacity']; ?></td>
                <td><?php echo $row['b_track_id']; ?></td>
                <td>
                    <a href="bus.php?edit=<?php echo $row['b_point_num']; ?>" class="update-btn">Update</a>
                    <a href="bus.php?delete=<?php echo $row['b_point_num']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
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
