<?php
if (isset($_POST["Submit-box"])) {
    // Capture form data
    $FullName = $_POST['Full_Name'];
    $Age = $_POST['Age'];
    $BirthDate = $_POST['Birth_Date'];
    $BloodType = isset($_POST['Blood_type']) ? trim($_POST['Blood_type']) : '';
    $Gender = isset($_POST['Gender']) ? trim($_POST['Gender']) : '';

    $FullName = strip_tags($FullName);
    $Age  = strip_tags($Age );
    $BirthDate = strip_tags($BirthDate);
    $BloodType = strip_tags($BloodType);
    $Gender = strip_tags($Gender);

    include 'db_connect.php';

    
if (!empty($FullName) && !empty($Age) && !empty($BirthDate) && !empty($BloodType) && !empty($Gender)) {
        // Prepare SQL query
        $sql = "INSERT INTO `blood_donors` (Full_Name, Age, Birth_Date, Blood_type, Gender) 
                    VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error in preparing the statement: " . $conn->error);
        }

        // Bind parameters correctly
        $stmt->bind_param("sssss", $FullName, $Age, $BirthDate, $BloodType, $Gender);

         // Execute the query and check the result
         if ($stmt->execute()) {
            echo "";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement and the connection
        $stmt->close();
        $conn->close();
    } else {
        echo "";
    }
}
?>