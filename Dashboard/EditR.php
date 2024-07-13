<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../Database/db_connect.php";

$FullName = $BirthDate = $BloodType = $Gender = '';
$id = '';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM blood_recipients WHERE Recipients_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $FullName = isset($row['Full_Name']) ? $row['Full_Name'] : '';
        $BirthDate = isset($row['Birth_Date']) ? $row['Birth_Date'] : '';
        $BloodType = isset($row['Blood_Type']) ? $row['Blood_Type'] : '';
        $Gender = isset($row['Gender']) ? $row['Gender'] : '';
    } else {
        echo "No donor found with ID: " . $id;
    }

    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Update-box"])) {

    $id = $_POST['Recipients_ID'];
    $FullName = $_POST['Full_Name'];
    $BirthDate = $_POST['Birth_Date'];
    $BloodType = isset($_POST['Blood_Type']) ? $_POST['Blood_Type'] : '';
    $Gender = isset($_POST['Gender']) ? $_POST['Gender'] : '';

    // Calculate age based on the new birth date
    $birthDate = new DateTime($BirthDate);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;

    $sql = "UPDATE blood_recipients SET Full_Name=?, Birth_Date=?, Blood_Type=?, Gender=?, Age=? WHERE Recipients_ID=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error in preparing the statement: " . $conn->error);
    }
    
    $stmt->bind_param("ssssii", $FullName, $BirthDate, $BloodType, $Gender, $age, $id);

    if ($stmt->execute()) {
        echo "Donor details updated successfully.";

        header("Location: View.php?id=" . $id);
        exit();
    } else {
        echo "Error updating donor details: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Blood Donor Details</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .container {
            max-width: 600px;
            width: 100%;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: #007bff;
        }
        .btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .form-footer {
            margin-top: 20px;
            text-align: center;
        }
        .form-footer a {
            color: #007bff;
            text-decoration: none;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>Blood Recipients Details</h1>
        </header>
        <form method="post">
            <div class="form-group">
                <label for="Full_Name">Full name:</label>
                <input type="text" name="Full_Name" id="Full_Name" class="form-control" value="<?php echo htmlspecialchars($FullName); ?>">
            </div>
            <div class="form-group">
                <label for="Birth_Date">Birth Date:</label>
                <input type="date" name="Birth_Date" id="Birth_Date" class="form-control" value="<?php echo htmlspecialchars($BirthDate); ?>">
            </div>
            <div class="form-group">
                <label for="Blood_Type">Blood Type:</label>
                <select class="form-control" name="Blood_Type" id="Blood_Type">
                    <option value="" disabled>Select Blood Type</option>
                    <option value="A-" <?php if ($BloodType == 'A-') echo 'selected'; ?>>A-</option>
                    <option value="A+" <?php if ($BloodType == 'A+') echo 'selected'; ?>>A+</option>
                    <option value="B-" <?php if ($BloodType == 'B-') echo 'selected'; ?>>B-</option>
                    <option value="B+" <?php if ($BloodType == 'B+') echo 'selected'; ?>>B+</option>
                    <option value="O-" <?php if ($BloodType == 'O-') echo 'selected'; ?>>O-</option>
                    <option value="O+" <?php if ($BloodType == 'O+') echo 'selected'; ?>>O+</option>
                    <option value="AB-" <?php if ($BloodType == 'AB-') echo 'selected'; ?>>AB-</option>
                    <option value="AB+" <?php if ($BloodType == 'AB+') echo 'selected'; ?>>AB+</option>
                </select>
            </div>
            <div class="form-group">
                <label for="Gender">Gender:</label>
                <select class="form-control" name="Gender" id="Gender">
                    <option value="" disabled>Select Gender</option>
                    <option value="Male" <?php if ($Gender == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($Gender == 'Female') echo 'selected'; ?>>Female</option>
                    <option value="Others" <?php if ($Gender == 'Others') echo 'selected'; ?>>Others</option>
                </select>
            </div>
            <input type="hidden" name="Recipients_ID" value="<?php echo $id; ?>">
            <div class="form-group">
                <input type="submit" name="Update-box" value="Edit" class="btn">
            </div>
        </form>
        <div class="form-footer">
            <a href='../Dashboard/View.php'>Back to Dashboard</a>
        </div>
    </div>
</body>

</html>
