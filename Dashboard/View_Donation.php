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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Donation Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
        }
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content-wrapper {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
            padding-bottom: 50px;
        }
        .container {
            max-width: 90%;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            margin-bottom: 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .footer {
            flex-shrink: 0;
            margin-top: auto;
        }
        h1 {
            color: #dc3545;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .donation-container {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 15px;
            width: 100%;
            max-width: 90%px;
        }
        .donation-container::-webkit-scrollbar {
            width: 8px;
        }
        .donation-container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .donation-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .donation-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        .donation-details {
            background-color: #f9f9f9;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        .donation-details h2 {
            color: #dc3545;
            font-size: 20px;
            margin-bottom: 15px;
        }
        .btn-primary {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .header-text {
            text-align: center;
            margin-bottom: 20px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <?php include ("../Design/navbar_user.php"); ?>
        
        <div class="container">
            <h1 class="text-center">Your Donation Details</h1>
            <p class="header-text">Thank you for your generous donations. Below you can find a record of your contributions.</p>

            <div class="donation-container">
                <?php
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
                    echo "<p class='alert alert-info'>You haven't submitted any donor details yet.</p>";
                }

                $stmt->close();
                $conn->close();
                ?>
            </div>

            <div class="text-center mt-4">
                <a href="../Main/user_dashboard.php" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <?php include ("../Design/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>
</html>