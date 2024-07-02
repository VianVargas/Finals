<?php
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    include '../Database/db_connect.php';

    $sql = "DELETE FROM blood_donors WHERE Donors_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: View.php");
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "ID parameter is missing.";
}
?>