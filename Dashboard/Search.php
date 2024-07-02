<header>
    <h1>Admin</h1>
</header>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Recipients</title>
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
        th[colspan='6'] {
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Search Recipients</h1>
    </header>

    <div class="form-container">
        <form method="post">
            <select class="form-control" name="Search" id="Search">
                <option value="" disabled selected>Recipients Name, Blood Type</option>
                <?php
                include '../Database/db_search.php';
                include '../Database/db_connect.php';

                // Fetch recipients from the database
                $sql = "SELECT Recipients_ID, Full_Name, Blood_type FROM blood_recipients";
                $result = mysqli_query($conn, $sql);

                if (!$result) {
                    die('Error: ' . mysqli_error($conn));
                }

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='"  . $row['Recipients_ID'] . "'>" .  $row['Full_Name'] . " [" . $row['Blood_type'] . "] " . "</option>";
                }

                // Close the database connection
                mysqli_close($conn);
                ?>
            </select>
            <br>
            <input type="submit" name="Search-box" value="View">
        </form>
    </div>

    <?php
    include '../Database/db_connect.php';

    function getCompatibleBloodTypes($bloodType) {
        $compatibilities = [
            'A-' => ['A-', 'A+', 'O-', 'O+'],
            'A+' => ['A+', 'AB+'],
            'B-' => ['B-', 'B+', 'O-', 'O+'],
            'B+' => ['B+', 'AB+'],
            'O-' => ['O-', 'O+'],
            'O+' => ['O+'],
            'AB-' => ['AB-', 'AB+'],
            'AB+' => ['AB+']
        ];
        
        if (isset($compatibilities[$bloodType])) {
            return $compatibilities[$bloodType];
        } else {
            return []; // Return an empty array if blood type not found
        }
    }


    if (isset($_POST['Search'])) {
        $selectedRecipientID = $_POST['Search'];
        
        if (!empty($selectedRecipientID)) {
            
        $sql = "SELECT Full_Name, Age, Birth_Date, Blood_type, Gender FROM blood_recipients WHERE Recipients_ID = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error in preparing the statement: " . $conn->error);
        }

        $stmt->bind_param("i", $selectedRecipientID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<br><br><br><br>";
            echo "<table>";
            echo "<tr>";
            echo "<th colspan='6'>Blood Donors Collection</th>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>Full Name</th>";
            echo "<th>Age</th>";
            echo "<th>Birth Date</th>";
            echo "<th>Blood Type</th>";
            echo "<th>Gender</th>";
            echo "<th>Blood Need</th>";
            echo "</tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . strip_tags($row['Full_Name']) . "</td>";
                echo "<td>" . strip_tags($row['Age']) . "</td>";
                echo "<td>" . strip_tags($row['Birth_Date']) . "</td>";
                echo "<td>" . strip_tags($row['Blood_type']) . "</td>";
                echo "<td>" . strip_tags($row['Gender']) . "</td>";
                
                // Get compatible blood types based on recipient's blood type
                $compatibleTypes = getCompatibleBloodTypes($row['Blood_type']);
                echo "<td>";
                echo implode(", ", $compatibleTypes); // Display compatible types as a comma-separated list
                echo "</td>";
                
                echo "</tr>";
            }
            echo "</table>";
        }
        
        $stmt->close();
        $conn->close();
    }
        } else {
         echo "No recipient found with the selected ID.";
        }
        

        
    ?>
    <br>
<a href = '../Design/Doctype.php'> click here to go back</a>
</body>
</html>
