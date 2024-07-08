<?php
session_start();
include '../Database/db_connect.php';

function canAcceptBlood($recipientBloodType, $donorBloodType) {
    $acceptableBloodTypes = [
        'A-' => ['A-', 'O-'],
        'A+' => ['A+', 'A-', 'O+', 'O-'],
        'B-' => ['B-', 'O-'],
        'B+' => ['B+', 'B-', 'O+', 'O-'],
        'AB-' => ['A-', 'B-', 'AB-', 'O-'],
        'AB+' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
        'O-' => ['O-'],
        'O+' => ['O+', 'O-'],
    ];
    return in_array($donorBloodType, $acceptableBloodTypes[$recipientBloodType]);
}

function autoAssignDonors($conn) {

    $donorSql = "SELECT * FROM blood_donors WHERE Donors_ID NOT IN (SELECT donor_id FROM donations)";
    $donors = mysqli_query($conn, $donorSql);
    $availableDonors = mysqli_fetch_all($donors, MYSQLI_ASSOC);

    $recipientSql = "SELECT * FROM blood_recipients ORDER BY priority DESC, Recipients_ID ASC";
    $recipients = mysqli_query($conn, $recipientSql);

    while ($recipient = mysqli_fetch_assoc($recipients)) {
        foreach ($availableDonors as $key => $donor) {
            if (canAcceptBlood($recipient['Blood_type'], $donor['Blood_Type'])) {
                
                $sql = "INSERT INTO donations (donor_id, recipient_id) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $donor['Donors_ID'], $recipient['Recipients_ID']);
                if ($stmt->execute()) {
                
                    $updateSql = "UPDATE blood_recipients SET status = 'Donated' WHERE Recipients_ID = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bind_param("i", $recipient['Recipients_ID']);
                    $updateStmt->execute();

                    unset($availableDonors[$key]);
                    break;
                }
            }
        }
    }
}

autoAssignDonors($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin: Blood Donation Management</title>
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
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f9f9f9;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #007bff;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin: Recipient Status</h1>

        <?php
        if (isset($_SESSION['priority_update_message']) && !empty($_SESSION['priority_update_message'])) {
            echo "<p>" . htmlspecialchars($_SESSION['priority_update_message']) . "</p>";
            unset($_SESSION['priority_update_message']);
        }

        $sql = "SELECT r.*, d.donor_id, bd.Full_Name as donor_name 
                FROM blood_recipients r 
                LEFT JOIN donations d ON r.Recipients_ID = d.recipient_id 
                LEFT JOIN blood_donors bd ON d.donor_id = bd.Donors_ID
                ORDER BY r.priority DESC, r.Recipients_ID ASC";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die('Error: ' . mysqli_error($conn));
        }

        echo "<table>";
        echo "<th>ID</th><th>Full Name</th><th>Age</th><th>Birth Date</th><th>Blood Type</th><th>Gender</th><th>Donor</th><th>Status</th><th>Priority</th></tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Recipients_ID']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Full_Name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Age']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Birth_Date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Blood_type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Gender']) . "</td>";
            echo "<td>" . ($row['donor_id'] ? htmlspecialchars($row['donor_name']) : 'None') . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>
                <form method='post' action='Update_priority.php' style='display:inline;'>
                    <input type='hidden' name='recipient_id' value='" . $row['Recipients_ID'] . "'>
                    <input type='submit' name='increase_priority' value='+'>
                    <input type='submit' name='decrease_priority' value='-'>
                </form>
            </td>";
            echo "</tr>";
        }

        echo "</table>";
        ?>

        <div class="back-link">
            <a href="../Main/admin_dashboard.php">Back to Admin Dashboard</a>
        </div>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>