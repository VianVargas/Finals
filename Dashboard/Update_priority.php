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

        mysqli_query($conn, "DELETE FROM donations");
        mysqli_query($conn, "UPDATE blood_recipients SET status = 'Pending'");

        autoAssignDonors($conn);

        $_SESSION['priority_update_message'] = "Priority updated and donations reassigned.";
    }
}

mysqli_close($conn);

header("Location: Status.php");
exit();
?>