<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

function db_connect() {
    $mysqli = require __DIR__ . '/../Database/db_connect.php';
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    return $mysqli;
}

function submit_donor_user() {
    if (!isset($_SESSION['user_email'])) {
        die("You must be logged in to submit donor details.");
    }

    $user_email = $_SESSION['user_email'];
    $conn = db_connect();

    if (!$conn) {
        die("Database connection failed.");
    }

    if (isset($_POST["Submit-box"])) {
        $FullName = strip_tags($_POST['Full_Name']);
        $BirthDate = strip_tags($_POST['Birth_Date']);
        $BloodType = isset($_POST['Blood_Type']) ? strip_tags(trim($_POST['Blood_Type'])) : '';
        $Gender = isset($_POST['Gender']) ? strip_tags(trim($_POST['Gender'])) : '';
        $CollectionDate = date('Y-m-d H:i:s');

        // Calculate age based on birth date
        $birthDate = new DateTime($BirthDate);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;

        // Check if age is between 18 and 65
        if ($age < 18 || $age > 65) {
            echo "Sorry, donors must be between 18 and 65 years old.";
            return;
        }

        // Check if this is the user's first donation
        $check_sql = "SELECT Full_Name FROM blood_donors WHERE Email = ? ORDER BY Collection_Date DESC LIMIT 1";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $user_email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        $is_first_donation = true;
        if ($result->num_rows > 0) {
            $is_first_donation = false;
            $row = $result->fetch_assoc();
            if ($row['Full_Name'] !== $FullName) {
                echo "Warning: The name does not match your previous donation record. Use the name from your first donation.";
                return;
            }
        }

        // Proceed with insertion
        if (!empty($FullName) && !empty($BirthDate) && !empty($BloodType) && !empty($Gender)) {
            $sql = "INSERT INTO `blood_donors` (Full_Name, Age, Birth_Date, Blood_type, Gender, Email, Collection_Date) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error in preparing the statement: " . $conn->error);
            }

            $stmt->bind_param("sisssss", $FullName, $age, $BirthDate, $BloodType, $Gender, $user_email, $CollectionDate);

            if ($stmt->execute()) {
                echo "Donor details submitted successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "All fields are required.";
        }
    }

    $conn->close();
}

function submit_recipient() {
    if (isset($_POST["Submit2-box"])) {
        $conn = db_connect();

        $FullName = strip_tags($_POST['Full_Name']);
        $Age = strip_tags($_POST['Age']);
        $BirthDate = strip_tags($_POST['Birth_Date']);
        $BloodType = isset($_POST['Blood_type']) ? strip_tags(trim($_POST['Blood_type'])) : '';
        $Gender = isset($_POST['Gender']) ? strip_tags(trim($_POST['Gender'])) : '';

        if (!empty($FullName) && !empty($Age) && !empty($BirthDate) && !empty($BloodType) && !empty($Gender)) {
            $sql = "INSERT INTO `blood_recipients` (Full_Name, Age, Birth_Date, Blood_type, Gender) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error in preparing the statement: " . $conn->error);
            }

            $stmt->bind_param("sssss", $FullName, $Age, $BirthDate, $BloodType, $Gender);

            if ($stmt->execute()) {
                echo "Recipient details submitted successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "All fields are required.";
        }

        $conn->close();
    }
}

function search_recipient() {
    if (isset($_POST["Search-box"])) {
        $conn = db_connect();

        $selectedRecipientID = $_POST['Search'];

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

            echo "Recipient Details:<br>";
            echo "Full Name: $FullName<br>";
            echo "Age: $Age<br>";
            echo "Birth Date: $BirthDate<br>";
            echo "Blood Type: $BloodType<br>";
            echo "Gender: $Gender<br>";
        } else {
            echo "No recipient found with the selected ID.";
        }

        $stmt->close();
        $conn->close();
    }
}

function fetchAnnouncements() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["announcement-box"])) {
        $conn = db_connect();
        $Title = strip_tags($_POST['Title']);
        $Announce = strip_tags($_POST['Announcement']);
        $Organizer = strip_tags($_POST['Organizer']);
        $DateEvent = $_POST['Date_Event'];

        if (!empty($Title) && !empty($Announce) && !empty($Organizer) && !empty($DateEvent)) {
            $sql = "INSERT INTO `announcements` (Title, Announcement, Organizer, Date_Event) 
                    VALUES (?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                $message = "Error in preparing the statement: " . $conn->error;
            } else {
                $stmt->bind_param("ssss", $Title, $Announce, $Organizer, $DateEvent);

                if ($stmt->execute()) {
                    $message = "Announcement added successfully.";
                    // Redirect to prevent resubmission on refresh
                    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
                    exit();
                } else {
                    $message = "Error: " . $stmt->error;
                }

                $stmt->close();
            }
        } else {
            $message = "All fields are required.";
        }
    }
}
?>