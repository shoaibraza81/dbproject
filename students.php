<?php
include('../includes/db_connect.php');

// Handle form submission for adding a new student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_student'])) {
    $s_name = mysqli_real_escape_string($conn, $_POST['name']);
    $s_phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $s_dept = mysqli_real_escape_string($conn, $_POST['department']);
    $s_semester = mysqli_real_escape_string($conn, $_POST['semester']);
    $s_point_num = mysqli_real_escape_string($conn, $_POST['point_number']);
    $s_stop = mysqli_real_escape_string($conn, $_POST['stop']);

    // Server-side validation for phone number
    if (!empty($s_name) && !empty($s_phone) && !empty($s_dept)) {
        if (!preg_match('/^\d{11}$/', $s_phone)) {
            $_SESSION['error'] = "Invalid phone number! It should be 11 digits.";
            header("Location: students.php");
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO student (s_name, s_phone, s_dept, s_semester, s_point_num, s_stop) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $s_name, $s_phone, $s_dept, $s_semester, $s_point_num, $s_stop);
        $stmt->execute();
        $_SESSION['message'] = "Student added successfully!";
        header("Location: students.php");
        exit();
    } else {
        $_SESSION['error'] = "All fields are required!";
    }
}

// Handle deleting a student
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM student WHERE s_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $_SESSION['message'] = "Student deleted successfully!";
    header("Location: students.php");
    exit();
}

// Handle updating a student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_student'])) {
    $id = intval($_POST['s_id']);
    $s_name = mysqli_real_escape_string($conn, $_POST['name']);
    $s_phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $s_dept = mysqli_real_escape_string($conn, $_POST['department']);
    $s_semester = mysqli_real_escape_string($conn, $_POST['semester']);
    $s_point_num = mysqli_real_escape_string($conn, $_POST['point_number']);
    $s_stop = mysqli_real_escape_string($conn, $_POST['stop']);

    // Server-side validation for phone number
    if (!preg_match('/^\d{11}$/', $s_phone)) {
        $_SESSION['error'] = "Invalid phone number! It should be 11 digits.";
        header("Location: students.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE student SET s_name = ?, s_phone = ?, s_dept = ?, s_semester = ?, s_point_num = ?, s_stop = ? WHERE s_id = ?");
    $stmt->bind_param("ssssssi", $s_name, $s_phone, $s_dept, $s_semester, $s_point_num, $s_stop, $id);
    $stmt->execute();
    $_SESSION['message'] = "Student updated successfully!";
    header("Location: students.php");
    exit();
}

// Handle search functionality
$search = '';
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM student WHERE s_name LIKE '%$search%' OR s_phone LIKE '%$search%' OR s_dept LIKE '%$search%'";
} else {
    $query = "SELECT * FROM student";
}
$result = mysqli_query($conn, $query);

// Fetch student data for updating
$updateData = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $updateResult = mysqli_query($conn, "SELECT * FROM student WHERE s_id = $id");
    $updateData = mysqli_fetch_assoc($updateResult);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
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

        .error-msg {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .search-container input[type="text"] {
            width: 80%;
            display: inline-block;
        }

        .search-container button {
            width: 18%;
            display: inline-block;
        }
    </style>
    <script>
        // Client-side phone number validation using JavaScript
        function validatePhoneNumber() {
            var phone = document.getElementById("phone").value;
            var phonePattern = /^\d{11}$/;
            if (!phonePattern.test(phone)) {
                alert("Invalid phone number! It should be 11 digits.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

    <div class="header">
        <img src="../images/logo.png" alt="Logo">
        <h1>Manage Students</h1>
    </div>

    <!-- Search Form -->
    <div class="search-container">
        <form method="GET" action="students.php">
            <input type="text" name="search" placeholder="Search by Name, Phone, or Department" value="<?php echo $search; ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="form-container">
        <h3><?php echo isset($updateData) ? 'Update Student' : 'Add New Student'; ?></h3>
        <form method="POST" action="students.php" onsubmit="return validatePhoneNumber()">
            <?php if (isset($updateData)): ?>
                <input type="hidden" name="s_id" value="<?php echo $updateData['s_id']; ?>">
            <?php endif; ?>
            <input type="text" name="name" placeholder="Name" value="<?php echo $updateData['s_name'] ?? ''; ?>" required>
            <input type="text" id="phone" name="phone" placeholder="Phone Number" value="<?php echo $updateData['s_phone'] ?? ''; ?>" required>
            <input type="text" name="department" placeholder="Department" value="<?php echo $updateData['s_dept'] ?? ''; ?>" required>
            <input type="text" name="semester" placeholder="Semester" value="<?php echo $updateData['s_semester'] ?? ''; ?>" required>
            <input type="text" name="point_number" placeholder="Point Number" value="<?php echo $updateData['s_point_num'] ?? ''; ?>" required>
            <input type="text" name="stop" placeholder="Stop" value="<?php echo $updateData['s_stop'] ?? ''; ?>" required>
            <button type="submit" name="<?php echo isset($updateData) ? 'update_student' : 'add_student'; ?>">
                <?php echo isset($updateData) ? 'Update Student' : 'Add Student'; ?>
            </button>
        </form>
    </div>

    <!-- Display error messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-msg">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Department</th>
            <th>Semester</th>
            <th>Point Number</th>
            <th>Stop</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['s_id']; ?></td>
                <td><?php echo $row['s_name']; ?></td>
                <td><?php echo $row['s_phone']; ?></td>
                <td><?php echo $row['s_dept']; ?></td>
                <td><?php echo $row['s_semester']; ?></td>
                <td><?php echo $row['s_point_num']; ?></td>
                <td><?php echo $row['s_stop']; ?></td>
                <td>
                    <a href="students.php?edit=<?php echo $row['s_id']; ?>" class="update-btn">Edit</a>
                    <a href="students.php?delete=<?php echo $row['s_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="go-back-btn">
        <a href="../dashboard.php">Go Back to Dashboard</a>
    </div>

</body>
</html>

<?php
// Close the connection
mysqli_close($conn);
?>
