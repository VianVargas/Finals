<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Compatible Recipients</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap');
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }
        body {
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            font-weight: 700;
        }
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .form-control {
            padding: 10px;
            margin: 10px 0;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: 700;
        }
        th[colspan='5'] {
            text-align: center;
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
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Search Compatible Recipients</h1>
    </header>

    <div class="form-container">
        <form method="post">
            <select class="form-control" name="bloodType" id="bloodType">
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
            <br>
            <input type="submit" name="search" value="Search Compatible Recipients">
        </form>
    </div>

    <?php
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
                echo "<table>";
                echo "<tr>";
                echo "<th colspan='5'>Compatible Recipients for Blood Type $selectedBloodType</th>";
                echo "</tr>";
                echo "<tr>";
                echo "<th>Full Name</th>";
                echo "<th>Age</th>";
                echo "<th>Birth Date</th>";
                echo "<th>Blood Type</th>";
                echo "<th>Gender</th>";
                echo "</tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['Full_Name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Age']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Birth_Date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Blood_type']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Gender']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No compatible recipients found for blood type $selectedBloodType.</p>";
            }
            
            $stmt->close();
        } else {
            echo "<p>Please select a blood type.</p>";
        }
        
        $conn->close();
    }
    ?>
    <br>
    <a href="../Main/user_dashboard.php" class="btn">Back to Dashboard</a>
</body>
</html>