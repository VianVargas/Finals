<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../Registration/authorize.php';
verify_session('admin');

$user_name = htmlspecialchars($_SESSION["user_name"]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }
        body {
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .welcome-message {
            text-align: center;
            margin-bottom: 20px;
        }
        .dashboard-links {
            list-style-type: none;
            padding: 0;
            text-align: center;
        }
        .dashboard-links li {
            margin-bottom: 10px;
        }
        .dashboard-links a {
            display: block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .dashboard-links a:hover {
            background-color: #0056b3;
        }
        .logout-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
        .logout-link a {
            color: #007bff;
            text-decoration: none;
        }
        .logout-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="welcome-message">
            <p>Welcome, <?php echo $user_name; ?>!</p>
        </div>
        <ul class="dashboard-links">
            <li><a href="../Dashboard/addR.php">Add Recipients Details</a></li>
            <li><a href="../Dashboard/View.php">View Blood Collection</a></li>
            <li><a href="../Dashboard/Status.php">Donor Status</a></li>
            <li><a href="../Dashboard/Announcement.php">Announcement View</a></li>
        </ul>
        <div class="logout-link">
            <a href="../Registration/logout.php" id="logout-link">Logout</a>
        </div>
        </div>

        <script>
            document.getElementById('logout-link').addEventListener('click', function(event) {
                event.preventDefault(); 
                
                if (confirm('Are you sure you want to logout?')) {
                    window.location.href = this.href;
                }
            });
        </script>
    </div>
</body>
</html>
