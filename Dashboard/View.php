<header>
    <h1>Admin</h1>
</header>
<?php

include '../Database/db_donors.php';
include '../Database/db_connect.php';

$sql = "SELECT Donors_ID, Full_Name, Age, Birth_Date, Blood_type, Gender FROM blood_donors";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Error: ' . mysqli_error($conn));
}
    // Define and output the style and table header once
    echo "<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap');
    * {
        text-align: center;
        padding: 0;
        margin: 40;
        box-sizing: border-box;
        font-family: 'Montserrat', sans-serif;
    }
    table {
        text-align: center;
        width: 50%;
        margin: auto;
        border-collapse: collapse;
    }
    table, th, td {
        border: 1px solid black;
    }
    th, td {
        padding: 8px;
        text-align: left;
    }
    th[colspan='6'] {
        text-align: center;
        font-weight: 700;
    }
    </style>";

    
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
    echo "<th>Update</th>"; 
    echo "</tr>";



    while ($row = mysqli_fetch_array($result)){
        echo "<tr>";
        echo "<td>". strip_tags($row['Full_Name']). "</td>";
        echo "<td>". strip_tags($row['Age']). "</td>";
        echo "<td>". strip_tags($row['Birth_Date']). "</td>";
        echo "<td>". strip_tags($row['Blood_type']). "</td>";
        echo "<td>". strip_tags($row['Gender']). "</td>";
        echo "<td><a href='Delete.php?id=" . $row['Donors_ID'] . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a></td>";
        echo "</tr>";
    }


    // Close the table tag
    echo "</table>";

    mysqli_close($conn);
        ?>

<br>
    <div class=".Center">
    <a href = '../Design/Doctype.php'> click here to go back</a>
    </div>
    

    </div>
</div>