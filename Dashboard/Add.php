<?php 
    require_once "../Database/db_functions.php";

    if (isset($_POST["Submit-box"])) {
        submit_donor_user();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donor Details</title>
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
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Blood Donor Details</h1>
        </header>

        <form method="post" action="">
            <div class="form-group">
                <label for="Full_Name">Full Name</label>
                <input type="text" name="Full_Name" id="Full_Name" class="form-control" placeholder="Eg. Fraizer Jethro G. Vargas" required>
            </div>
            <div class="form-group">
                <label for="Birth_Date">Birth Date</label>
                <input type="date" name="Birth_Date" id="Birth_Date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="Blood_type">Blood Type</label>
                <select class="form-control" name="Blood_Type" id="Blood_Type" required>
                    <option value="" disabled selected>Select Blood Type</option>
                    <option value='A-'>A-</option>
                    <option value='A+'>A+</option>
                    <option value='B-'>B-</option>
                    <option value='B+'>B+</option>
                    <option value='O-'>O-</option>
                    <option value='O+'>O+</option>
                    <option value='AB-'>AB-</option>
                    <option value='AB+'>AB+</option>
                </select>
            </div>
            <div class="form-group">
                <label for="Gender">Gender</label>
                <select class="form-control" name="Gender" id="Gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <?php
            if (isset($_SESSION['message'])) {
                echo "<div class='success'>" . htmlspecialchars($_SESSION['message']) . "</div>";
                unset($_SESSION['message']);
            } elseif (isset($_SESSION['error'])) {
                echo "<div class='error'>" . htmlspecialchars($_SESSION['error']) . "</div>";
                unset($_SESSION['error']);
            }
            ?>
            
            <br>
            <div class="form-group">
                <input type="submit" name="Submit-box" value="Donate" class="btn">
            </div>
        </form>
        <div class="form-footer">
            <a href="../Main/user_dashboard.php">Click here to go back</a>
        </div>
    </div>
</body>
</html>