<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../Registration/authorize.php';
verify_session('user'); 

$user_name = htmlspecialchars($_SESSION["user_name"]);

include '../Database/db_connect.php';

function getCompatibleRecipients($donorBloodType) {
    $compatibilities = [
        'A-' => ['A-', 'A+', 'AB-', 'AB+'],
        'A+' => ['A+', 'AB+'],
        'B-' => ['B-', 'B+', 'AB-', 'AB+'],
        'B+' => ['B+', 'AB+'],
        'O-' => ['A-', 'A+', 'B-', 'B+', 'O-', 'O+', 'AB-', 'AB+'],
        'O+' => ['A+', 'B+', 'O+', 'AB+'],
        'AB-' => ['AB-', 'AB+'],
        'AB+' => ['AB+']
    ];
    
    return isset($compatibilities[$donorBloodType]) ? $compatibilities[$donorBloodType] : [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Compatible Recipients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
        }
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
            display: flex;
            flex-direction: column;
            padding-bottom: 50px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            margin-bottom: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 95%;
            max-width: 90%;
        }
        .footer {
            flex-shrink: 0;
            margin-top: auto;
        }
        h1 {
            color: #dc3545;
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 1.8rem;
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
        .table {
            margin-top: 20px;
        }
        .table th {
            background-color: #dc3545;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        .scrollable-results {
            max-height: 400px;
            overflow-y: auto;
            margin-top: 20px;
            width: 100%;
        }
        .scrollable-results::-webkit-scrollbar {
            width: 8px;
        }
        .scrollable-results::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .scrollable-results::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .scrollable-results::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        @media (min-width: 576px) {
            .container {
                padding: 30px;
            }
            h1 {
                font-size: 2rem;
            }
        }
        @media (min-width: 768px) {
            .container {
                width: 90%;
            }
            h1 {
                font-size: 2.2rem;
            }
        }
        @media (min-width: 992px) {
            .container {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <?php include ("../Design/navbar_user.php"); ?>
        
        <div class="container">
            <h1 class="text-center">Search Compatible Recipients</h1>

            <form method="post" class="mb-4 w-100">
                <div class="mb-3">
                    <label for="bloodType" class="form-label">Select your blood type:</label>
                    <select class="form-select" name="bloodType" id="bloodType" required>
                        <option value="" disabled selected>Select your blood type</option>
                        <option value="A-">A-</option>
                        <option value="A+">A+</option>
                        <option value="B-">B-</option>
                        <option value="B+">B+</option>
                        <option value="O-">O-</option>
                        <option value="O+">O+</option>
                        <option value="AB-">AB-</option>
                        <option value="AB+">AB+</option>
                    </select>
                </div>
                <button type="submit" name="search" class="btn btn-primary">Search Compatible Recipients</button>
            </form>

            <?php
            if (isset($_POST['search']) && isset($_POST['bloodType'])) {
                $selectedBloodType = $_POST['bloodType'];
                
                if (!empty($selectedBloodType)) {
                    $compatibleTypes = getCompatibleRecipients($selectedBloodType);
                    $placeholders = implode(',', array_fill(0, count($compatibleTypes), '?'));
                    
                    $sql = "SELECT Full_Name, Age, Birth_Date, Blood_type, Gender FROM blood_recipients WHERE Blood_type IN ($placeholders)";
                    $stmt = $conn->prepare($sql);
                    
                    if ($stmt === false) {
                        die("Error in preparing the statement: " . $conn->error);
                    }

                    $stmt->bind_param(str_repeat('s', count($compatibleTypes)), ...$compatibleTypes);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        echo "<h4 class='mt-4 text-center'>Compatible Recipients for Blood Type $selectedBloodType</h4>";
                        echo "<div class='scrollable-results'>";
                        echo "<div class='table-responsive'>";
                        echo "<table class='table table-striped table-hover'>";
                        echo "<thead><tr><th>Full Name</th><th>Age</th><th>Birth Date</th><th>Blood Type</th><th>Gender</th></tr></thead>";
                        echo "<tbody>";

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['Full_Name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Age']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Birth_Date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Blood_type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Gender']) . "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody></table>";
                        echo "</div>";
                        echo "</div>";
                    } else {
                        echo "<div class='alert alert-info mt-4' role='alert'>No compatible recipients found for blood type $selectedBloodType.</div>";
                    }
                    
                    $stmt->close();
                } else {
                    echo "<div class='alert alert-warning mt-4' role='alert'>Please select a blood type.</div>";
                }
                
                $conn->close();
            }
            ?>

            <div class="text-center mt-4">
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