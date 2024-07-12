<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap');
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }
        body {
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            font-weight: 700;
        }
        .details-container {
            max-width: 800px;
            width: 100%;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
        }
        .donation-details {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .donation-details p {
            margin-bottom: 10px;
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
    <header>
        <h1>Your Donation Details</h1>
    </header>
    <div class="details-container">

    <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        session_start();
        include "../Database/db_connect.php";

        if (!isset($_SESSION['user_email'])) {
            die("You must be logged in to view donation details.");
        }

        $user_email = $_SESSION['user_email'];

        $sql = "SELECT * FROM blood_donors WHERE Email = ? ORDER BY Collection_Date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $donationCount = 1;
            while ($donor = $result->fetch_assoc()) {
                echo "<div class='donation-details'>";
                echo "<h2>Donation #" . $donationCount . "</h2>";
                echo "<p><strong>Name:</strong> " . htmlspecialchars($donor['Full_Name']) . "</p>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($user_email) . "</p>";
                echo "<p><strong>Age:</strong> " . htmlspecialchars($donor['Age']) . "</p>";
                echo "<p><strong>Birth Date:</strong> " . htmlspecialchars($donor['Birth_Date']) . "</p>";
                echo "<p><strong>Blood Type:</strong> " . htmlspecialchars($donor['Blood_Type']) . "</p>";
                echo "<p><strong>Gender:</strong> " . htmlspecialchars($donor['Gender']) . "</p>";
                echo "<p><strong>Collection Date:</strong> " . htmlspecialchars($donor['Collection_Date']) . "</p>";
                echo "</div>";
                $donationCount++;
            }
        } else {
            echo "<p>You haven't submitted any donor details yet.</p>";
        }

        $stmt->close();
        $conn->close();
        ?>

        <a href="../Main/user_dashboard.php" class="btn">Back to Dashboard</a>
    </div>
</body>
</html>