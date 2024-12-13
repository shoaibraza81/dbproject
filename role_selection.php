<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Role - FAST University</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- FontAwesome for icons -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            background-image: url('images/background.jpg'); /* Replace with the correct path */
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #fff;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.7);
            width: 400px;
        }

        h2 {
            font-size: 36px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        h3 {
            font-size: 24px;
            margin-bottom: 30px;
        }

        .role-button {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #28a745;
            color: white;
            padding: 15px;
            margin: 15px 0;
            text-decoration: none;
            border-radius: 8px;
            font-size: 20px;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
            width: 100%;
        }

        .role-button i {
            margin-right: 10px;
            font-size: 24px;
        }

        .role-button:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .admin-button {
            background-color: #ffc107;
        }

        .admin-button:hover {
            background-color: #e0a800;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>FAST UNIVERSITY</h2>
        <h3>Select Your Role</h3>
        
        <a href="login.php?role=admin" class="role-button admin-button">
            <i class="fas fa-user-shield"></i> Admin
        </a>
        
        <a href="login.php?role=student" class="role-button">
            <i class="fas fa-user-graduate"></i> Student
        </a>
    </div>
</body>
</html>
