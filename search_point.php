<?php
include('../includes/db_connect.php');

$searchResults = [];
if (isset($_POST['search_stop'])) {
    // Sanitize the input stop name
    $search_stop = mysqli_real_escape_string($conn, $_POST['stop_name']);
    
    // Query to search point numbers based on stop name
    $search_query = "SELECT * FROM route WHERE r_stop LIKE '%$search_stop%'";
    $searchResults = mysqli_query($conn, $search_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Point Number by Stop</title>
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
        <h2>Search Point Number by Stop</h2>

        <!-- Search Form -->
        <form method="POST" action="search_point.php">
            <input type="text" name="stop_name" placeholder="Enter Stop Name" required>
            <button type="submit" name="search_stop">Search</button>
        </form>

        <?php if (!empty($searchResults)): ?>
            <h4>Search Results</h4>
            <table>
                <tr>
                    <th>Route Point</th>
                    <th>Stop</th>
                    <th>Time</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($searchResults)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['r_point_num']); ?></td>
                        <td><?php echo htmlspecialchars($row['r_stop']); ?></td>
                        <td><?php echo htmlspecialchars($row['r_time']); ?></td>
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
