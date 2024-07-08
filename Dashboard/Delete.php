<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    include '../Database/db_connect.php';

    $conn->begin_transaction();

    try {

        $delete_donations_sql = "DELETE FROM donations WHERE donor_id = ?";
        $delete_donations_stmt = $conn->prepare($delete_donations_sql);
        $delete_donations_stmt->bind_param("i", $id);
        $delete_donations_stmt->execute();
        $delete_donations_stmt->close();

        $delete_donor_sql = "DELETE FROM blood_donors WHERE Donors_ID = ?";
        $delete_donor_stmt = $conn->prepare($delete_donor_sql);
        $delete_donor_stmt->bind_param("i", $id);
        
        if ($delete_donor_stmt->execute()) {
            $affected_rows = $delete_donor_stmt->affected_rows;
            if ($affected_rows > 0) {
                $conn->commit();
                header('Location: View.php');
            }
        }
        
        $delete_donor_stmt->close();
    } catch (Exception $e) {

        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
} else {
    echo "ID parameter is missing.";
}
?>