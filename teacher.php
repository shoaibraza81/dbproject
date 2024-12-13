<?php
include('../includes/db_connect.php');

// Handle form submission for adding a new teacher
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_teacher'])) {
    $t_name = mysqli_real_escape_string($conn, $_POST['name']);
    $t_point_num = mysqli_real_escape_string($conn, $_POST['point_number']);
    $t_stop = mysqli_real_escape_string($conn, $_POST['stop']);

    if (!empty($t_name) && !empty($t_point_num) && !empty($t_stop)) {
        $query = "INSERT INTO teacher (t_name, t_point_num, t_stop) 
                  VALUES ('$t_name', '$t_point_num', '$t_stop')";
        mysqli_query($conn, $query);
        header("Location: teacher.php");
        exit();
    }
}

// Handle deleting a teacher
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM teacher WHERE t_id = $id");
    header("Location: teacher.php");
    exit();
}

// Handle updating a teacher
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_teacher'])) {
    $id = intval($_POST['t_id']);
    $t_name = mysqli_real_escape_string($conn, $_POST['name']);
    $t_point_num = mysqli_real_escape_string($conn, $_POST['point_number']);
    $t_stop = mysqli_real_escape_string($conn, $_POST['stop']);

    $query = "UPDATE teacher SET 
              t_name = '$t_name', 
              t_point_num = '$t_point_num', 
              t_stop = '$t_stop' 
              WHERE t_id = $id";
    mysqli_query($conn, $query);
    header("Location: teacher.php");
    exit();
}

// Handle search functionality
$search = '';
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM teacher WHERE 
              t_name LIKE '%$search%' OR 
              t_point_num LIKE '%$search%' OR 
              t_stop LIKE '%$search%'";
} else {
    $query = "SELECT * FROM teacher";
}
$result = mysqli_query($conn, $query);

// Fetch teacher data for updating
$updateData = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $updateResult = mysqli_query($conn, "SELECT * FROM teacher WHERE t_id = $id");
    $updateData = mysqli_fetch_assoc($updateResult);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Teachers</title>
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
        <h1>Manage Teachers</h1>
    </div>

    <div class="form-container">
        <h3><?php echo isset($updateData) ? 'Update Teacher' : 'Add New Teacher'; ?></h3>
        <form method="POST" action="teacher.php">
            <?php if (isset($updateData)): ?>
                <input type="hidden" name="t_id" value="<?php echo $updateData['t_id']; ?>">
            <?php endif; ?>
            <input type="text" name="name" placeholder="Name" value="<?php echo $updateData['t_name'] ?? ''; ?>" required>
            <input type="text" name="point_number" placeholder="Point Number" value="<?php echo $updateData['t_point_num'] ?? ''; ?>" required>
            <input type="text" name="stop" placeholder="Stop" value="<?php echo $updateData['t_stop'] ?? ''; ?>" required>
            <button type="submit" name="<?php echo isset($updateData) ? 'update_teacher' : 'add_teacher'; ?>">
                <?php echo isset($updateData) ? 'Update Teacher' : 'Add Teacher'; ?>
            </button>
        </form>
    </div>

    <div class="search-container">
        <h3>Search Teachers</h3>
        <form method="GET" action="teacher.php">
            <input type="text" name="search" placeholder="Search by Name, Point Number, or Stop" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Point Number</th>
            <th>Stop</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['t_id']; ?></td>
                <td><?php echo $row['t_name']; ?></td>
                <td><?php echo $row['t_point_num']; ?></td>
                <td><?php echo $row['t_stop']; ?></td>
                <td>
                    <a href="teacher.php?edit=<?php echo $row['t_id']; ?>" class="update-btn">Update</a>
                    <a href="teacher.php?delete=<?php echo $row['t_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
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
