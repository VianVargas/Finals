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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content-wrapper {
            flex: 1 0 auto;
        }
        .footer {
            flex-shrink: 0;
        }
        .container {
            max-width: 90%;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        .btn-primary {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover, .btn-secondary:focus {
            background-color: #5a6268;
            border-color: #545b62;
        }
        h1 {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <?php include ("../Design/navbar_user.php"); ?>
        
        <div class="container">
            <h1 class="mb-4">Blood Donor Details</h1>

            <form method="post" action="">
                <div class="mb-3">
                    <label for="Full_Name" class="form-label">Full Name</label>
                    <input type="text" name="Full_Name" id="Full_Name" class="form-control" placeholder="Eg. Fraizer Jethro G. Vargas" required>
                </div>
                <div class="mb-3">
                    <label for="Birth_Date" class="form-label">Birth Date</label>
                    <input type="date" name="Birth_Date" id="Birth_Date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="Blood_Type" class="form-label">Blood Type</label>
                    <select class="form-select" name="Blood_Type" id="Blood_Type" required>
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
                <div class="mb-3">
                    <label for="Gender" class="form-label">Gender</label>
                    <select class="form-select" name="Gender" id="Gender" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Others">Others</option>
                    </select>
                </div>

                <?php
                if (isset($_SESSION['message'])) {
                    echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['message']) . "</div>";
                    unset($_SESSION['message']);
                } elseif (isset($_SESSION['error'])) {
                    echo "<div class='alert alert-danger'>" . htmlspecialchars($_SESSION['error']) . "</div>";
                    unset($_SESSION['error']);
                }
                ?>
                
                <div class="mb-3">
                    <button type="submit" name="Submit-box" class="btn btn-primary">Donate</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <a href="../Main/user_dashboard.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <?php include ("../Design/footer.php"); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>
</html>