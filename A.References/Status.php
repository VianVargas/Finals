<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../Registration/authorize.php';
    verify_session('admin');

    //include '../Database/db_donors_admin.php';
    include '../Database/db_connect.php';

    $sql = "SELECT Donors_ID, Full_Name, Age, Birth_Date, Blood_type, Gender FROM blood_donors";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die('Error: ' . mysqli_error($conn));
    }

    // Fetch donor and recipient counts grouped by blood type
    $donorCountQuery = "SELECT Blood_type, COUNT(*) as donor_count FROM blood_donors GROUP BY Blood_type";
    $recipientCountQuery = "SELECT Blood_type, COUNT(*) as recipient_count FROM blood_recipients GROUP BY Blood_type";

    $donorCountResult = mysqli_query($conn, $donorCountQuery);
    $recipientCountResult = mysqli_query($conn, $recipientCountQuery);

    if (!$donorCountResult || !$recipientCountResult) {
        die('Query error: ' . mysqli_error($conn));
    }

    // Create associative arrays to store donor and recipient counts
    $donorCounts = [];
    $recipientCounts = [];

    while ($row = mysqli_fetch_assoc($donorCountResult)) {
        $donorCounts[$row['Blood_type']] = $row['donor_count'];
    }

    while ($row = mysqli_fetch_assoc($recipientCountResult)) {
        $recipientCounts[$row['Blood_type']] = $row['recipient_count'];
    }

    // Fetch recipient details
    $recipientQuery = "SELECT * FROM blood_recipients";
    $recipientResult = mysqli_query($conn, $recipientQuery);

    if (!$recipientResult) {
        die('Query error: ' . mysqli_error($conn));
    }
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
            max-width: 800px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .center {
            text-align: center;
            margin-top: 20px;
        }
        .center a {
            text-decoration: none;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .center a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Admin Dashboard</h1>
        </header>
        
        <table>
            <thead>
                <tr>
                    <th colspan="6">Blood Recipients Status</th>
                </tr>
                <tr>
                    <th>Full Name</th>
                    <th>Age</th>
                    <th>Birth Date</th>
                    <th>Blood Type</th>
                    <th>Gender</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_array($recipientResult)) {
                    $recipientBloodType = strip_tags($row['Blood_type']);

                    echo "<tr>";
                    echo "<td>" . strip_tags($row['Full_Name']) . "</td>";
                    echo "<td>" . strip_tags($row['Age']) . "</td>";
                    echo "<td>" . strip_tags($row['Birth_Date']) . "</td>";
                    echo "<td>" . $recipientBloodType . "</td>";
                    echo "<td>" . strip_tags($row['Gender']) . "</td>";

                    // Determine if the recipient status is 'Donated' or 'Pending'
                    if (isset($donorCounts[$recipientBloodType]) && isset($recipientCounts[$recipientBloodType])) {
                        if ($donorCounts[$recipientBloodType] > 0) {
                            echo "<td>Received</td>";
                            // Update the donor count
                            $donorCounts[$recipientBloodType]--;
                        } else {
                            echo "<td>Pending</td>";
                        }
                    } else {
                        echo "<td>Pending</td>";
                    }

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="center">
            <a href="../Main/admin_dashboard.php">Click here to go back</a>
        </div>
    </div>
</body>
</html>
