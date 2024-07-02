<?php
if (isset($_POST["Search-box"])) {
    $selectedRecipientID = $_POST['Search'];

    include 'db_connect.php';

    // Fetch the selected recipient's details from the database
    $sql = "SELECT Full_Name, Age, Birth_Date, Blood_type, Gender FROM blood_recipients WHERE Recipients_ID = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error in preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("i", $selectedRecipientID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $FullName = strip_tags($row['Full_Name']);
        $Age = strip_tags($row['Age']);
        $BirthDate = strip_tags($row['Birth_Date']);
        $BloodType = strip_tags($row['Blood_type']);
        $Gender = strip_tags($row['Gender']);

        // Output or process the recipient's details as needed
        echo "Recipient Details:<br>";
        echo "Full Name: $FullName<br>";
        echo "Age: $Age<br>";
        echo "Birth Date: $BirthDate<br>";
        echo "Blood Type: $BloodType<br>";
        echo "Gender: $Gender<br>";
    } else {
        echo "No recipient found with the selected ID.";
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}
?>
