<?php
include('../includes/db_connect.php');

$driverSearchResults = [];
if (isset($_POST['search_driver'])) {
    // Sanitize the input point number
    $search_point = mysqli_real_escape_string($conn, $_POST['point_number']);
    
    // Query to search drivers by point number
    $driver_search_query = "SELECT * FROM driver WHERE d_point_num LIKE '%$search_point%'";
    $driverSearchResults = mysqli_query($conn, $driver_search_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Driver by Point Number</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- FontAwesome for icons -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 300px;
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            background: linear-gradient(45deg, #17a2b8, #28a745);
            color: white;
            padding: 12px 25px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
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

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .go-back-btn {
            text-align: center;
            margin-top: 20px;
        }

        .go-back-btn a {
            background-color: #ffc107;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .go-back-btn a:hover {
            background-color: #e0a800;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Search for Driver by Point Number</h2>

        <!-- Search Form -->
        <form method="POST" action="search_driver.php">
            <input type="text" name="point_number" placeholder="Enter Point Number" required>
            <button type="submit" name="search_driver">Search</button>
        </form>

        <?php if (!empty($driverSearchResults)): ?>
            <h4>Search Results</h4>
            <table>
                <tr>
                    <th>Driver Name</th>
                    <th>Phone</th>
                    <th>Point Number</th>
                </tr>
                <?php while ($driver = mysqli_fetch_assoc($driverSearchResults)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($driver['d_name']); ?></td>
                        <td><?php echo htmlspecialchars($driver['d_phone']); ?></td>
                        <td><?php echo htmlspecialchars($driver['d_point_num']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>

        <!-- Go Back Button -->
        <div class="go-back-btn">
            <a href="student_dashboard.php">Go Back to Dashboard</a>
        </div>
    </div>

</body>
</html>

<?php mysqli_close($conn); ?>
