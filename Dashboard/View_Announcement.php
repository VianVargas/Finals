<?php
include '../Database/db_connect.php';

$sql = "SELECT Title, Announcement, Organizer, Date_Event FROM announcements ORDER BY Date_Event DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Error: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Announcements</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; 
            flex-direction: column;
        }
        .container {
            max-width: 800px;
            width: 100%;
        }
        .announcement {
            background-color: #fff;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            position: relative;
            word-wrap: break-word; /* Ensure long words break properly */
        }

        .announcement h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .announcement p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .announcement .meta {
            font-size: 14px;
            color: #777;
            position: absolute;
            top: 10px; 
            right: 10px; 
        }
        .meta-item {
            margin-right: 10px; 
        }
        .Center {
            text-align: center;
            margin-top: 20px;
        }
        .Center a {
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
        }
        .Center a:hover {
            text-decoration: underline;
        }
        .btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Announcements</h1>
            <br>
        </header>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="announcement">
                    <h2><?php echo htmlspecialchars($row['Title']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($row['Announcement'])); ?></p>
                    <div class="meta">
                        <span class="meta-item"><strong>Organizer:</strong> <?php echo htmlspecialchars($row['Organizer']); ?></span>
                        <span class="meta-item"><strong>Date Event:</strong> <?php echo htmlspecialchars($row['Date_Event']); ?></span>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No announcements found.</p>
        <?php endif; ?>
        
    </div>

        <a href="../Main/user_dashboard.php" class="btn">Dashboard</a>
</body>
</html>


