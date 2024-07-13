<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        th[colspan='7'] {
            text-align: center;
        }
        th[colspan='6'] {
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin</h1>
    </header>
    <?php
    

    include '../Database/db_connect.php';

    $sql = "SELECT Donors_ID, Full_Name, Age, Birth_Date, Blood_Type, Gender, Collection_Date FROM blood_donors";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die('Error: ' . mysqli_error($conn));
    }

    echo "<table>";
    echo "<tr>";
    echo "<th colspan='7'>Blood Donors Collection</th>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Full Name</th>";
    echo "<th>Age</th>"; 
    echo "<th>Birth Date</th>";
    echo "<th>Blood Type</th>";
    echo "<th>Gender</th>"; 
    echo "<th>Collection Date</th>"; 
    echo "<th>Action</th>"; 
    echo "</tr>";

    while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>". strip_tags($row['Full_Name']). "</td>";
        echo "<td>". strip_tags($row['Age']). "</td>";
        echo "<td>". strip_tags($row['Birth_Date']). "</td>";
        echo "<td>". strip_tags($row['Blood_Type']). "</td>";
        echo "<td>". strip_tags($row['Gender']). "</td>";
        echo "<td>". strip_tags($row['Collection_Date']). "</td>";
        echo "<td><a href='Delete.php?id=" . $row['Donors_ID'] . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a> <a href='Edit.php?id=" . $row['Donors_ID'] . "' onclick='return confirm(\"Are you sure you want to Edit\")'>Edit</a></td>";
        echo "</tr>";
    }

    echo "</table>";

    // Blood Recipients Table
    $sql_recipients = "SELECT Recipients_ID, Full_Name, Age, Birth_Date, Blood_type, Gender FROM blood_recipients";
    $result_recipients = mysqli_query($conn, $sql_recipients);

    if (!$result_recipients) {
        die('Error: ' . mysqli_error($conn));
    }

    echo "<table>";
    echo "<tr>";
    echo "<th colspan='6'>Blood Recipients Collection</th>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Full Name</th>";
    echo "<th>Age</th>"; 
    echo "<th>Birth Date</th>";
    echo "<th>Blood Type</th>";
    echo "<th>Gender</th>"; 
    echo "<th>Action</th>"; 
    echo "</tr>";

    while ($row = mysqli_fetch_array($result_recipients)) {
        echo "<tr>";
        echo "<td>". strip_tags($row['Full_Name']). "</td>";
        echo "<td>". strip_tags($row['Age']). "</td>";
        echo "<td>". strip_tags($row['Birth_Date']). "</td>";
        echo "<td>". strip_tags($row['Blood_type']). "</td>";
        echo "<td>". strip_tags($row['Gender']). "</td>";
        echo "<td><a href='DeleteR.php?id=" . $row['Recipients_ID'] . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a> <a href='EditR.php?id=" . $row['Recipients_ID'] . "' onclick='return confirm(\"Are you sure you want to Edit\")'>Edit</a></td>";
        echo "</tr>";
    }

    echo "</table>";

    mysqli_close($conn);
    ?>

    <br>

    <div class="Center">
        <a href="../Main/admin_dashboard.php">Click here to go back</a>
    </div>

</body>
</html>
