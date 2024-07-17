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
        // Get all available donors
        $donorSql = "SELECT * FROM blood_donors WHERE Donors_ID NOT IN (SELECT donor_id FROM donations)";
        $donors = mysqli_query($conn, $donorSql);
        $availableDonors = mysqli_fetch_all($donors, MYSQLI_ASSOC);

        // Get all recipients who haven't been assigned a donor yet
        $recipientSql = "SELECT * FROM blood_recipients WHERE status = 'Pending' ORDER BY priority DESC, Recipients_ID ASC";
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

                        // Remove the assigned donor from the available donors list
                        unset($availableDonors[$key]);
                        break;
                    }
                }
            }
        }
    }

    // Handle priority updates
    if (isset($_POST['recipient_id'])) {
        $recipient_id = $_POST['recipient_id'];
        
        if (isset($_POST['increase_priority'])) {
            $sql = "UPDATE blood_recipients SET priority = priority + 1 WHERE Recipients_ID = ?";
        } elseif (isset($_POST['decrease_priority'])) {
            $sql = "UPDATE blood_recipients SET priority = GREATEST(priority - 1, 0) WHERE Recipients_ID = ?";
        }

        if (isset($sql)) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $recipient_id);
            $stmt->execute();

            // Reset all assignments
            mysqli_query($conn, "DELETE FROM donations");
            mysqli_query($conn, "UPDATE blood_recipients SET status = 'Pending'");

            // Run auto-assign process
            autoAssignDonors($conn);

            $_SESSION['priority_update_message'] = "Priority updated and donations reassigned.";
        }
    }

    // Run auto-assign process on page load
    autoAssignDonors($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin: Blood Donation Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            max-width: 90%;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #dc3545;
            font-weight: bold;
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
            color: #fff;
            text-decoration: none;
        }
        input[type="submit"] {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
<?php include ("../Design/navbar_user.php"); ?>
    <div class="container">
        
        <h1>Admin: Recipient Status</h1>

        <?php
        if (isset($_SESSION['priority_update_message']) && !empty($_SESSION['priority_update_message'])) {
            echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['priority_update_message']) . "</div>";
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

        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>ID</th><th>Full Name</th><th>Age</th><th>Birth Date</th><th>Blood Type</th><th>Gender</th><th>Donor</th><th>Status</th><th>Priority</th></tr></thead>";
        echo "<tbody>";

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
                <form method='post' action='Status.php' style='display:inline;'>
                    <input type='hidden' name='recipient_id' value='" . $row['Recipients_ID'] . "'>
                    <input type='submit' name='increase_priority' value='+' class='btn btn-sm btn-danger'>
                    <input type='submit' name='decrease_priority' value='-' class='btn btn-sm btn-danger'>
                </form>
            </td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
        ?>

        <div class="back-link">
            <a href="../Main/admin_dashboard.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Admin Dashboard
            </a>
        </div>
    </div>
    <?php include ("../Design/footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>