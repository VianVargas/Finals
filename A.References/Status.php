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
    echo "<th colspan='6'>Blood Recipients Status</th>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Full Name</th>";
    echo "<th>Age</th>"; 
    echo "<th>Birth Date</th>";
    echo "<th>Blood Type</th>";
    echo "<th>Gender</th>"; 
    echo "<th>Status</th>"; 
    echo "</tr>";



    function canAcceptBlood($recipientBloodType, $donorBloodType) {
        $acceptableBloodTypes = [
            'A-' => ['A-', 'A+', 'AB-', 'AB+'],
            'A+' => ['A+', 'AB+'],
            'AB-' => ['AB-', 'AB+'],
            'AB+' => ['AB+'],
            'B-' => ['B-', 'B+', 'AB-', 'AB+'],
            'B+' => ['B+', 'AB+'],
            'O-' => ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'],
            'O+' => ['O+', 'A+', 'B+', 'AB+'],
        ];
    
        return in_array($donorBloodType, $acceptableBloodTypes[$recipientBloodType]);
    }
    
    // Fetch donor counts grouped by blood type
    $donorCountQuery = "SELECT Blood_type, COUNT(*) as donor_count FROM blood_donors GROUP BY Blood_type";
    $donorCountResult = mysqli_query($conn, $donorCountQuery);
    
    if (!$donorCountResult) {
        die('Query error: ' . mysqli_error($conn));
    }
    
    // Create associative array to store donor counts
    $donorCounts = [];
    
    while ($row = mysqli_fetch_assoc($donorCountResult)) {
        $donorCounts[$row['Blood_type']] = $row['donor_count'];
    }
    
    // Fetch recipient details
    $recipientQuery = "SELECT * FROM blood_recipients";
    $recipientResult = mysqli_query($conn, $recipientQuery);
    
    if (!$recipientResult) {
        die('Query error: ' . mysqli_error($conn));
    }
    while ($row = mysqli_fetch_assoc($recipientResult)) {
        $recipientBloodType = strip_tags($row['Blood_type']);
    
        echo "<tr>";
        echo "<td>" . strip_tags($row['Full_Name']) . "</td>";
        echo "<td>" . strip_tags($row['Age']) . "</td>";
        echo "<td>" . strip_tags($row['Birth_Date']) . "</td>";
        echo "<td>" . $recipientBloodType . "</td>";
        echo "<td>" . strip_tags($row['Gender']) . "</td>";
    
        // Determine if the recipient status is 'Donated' or 'Pending'
        if (isset($donorCounts[$recipientBloodType]) && $donorCounts[$recipientBloodType] > 0) {
            // Example donor blood type
            $donorBloodType = 'AB+';
    
            if (canAcceptBlood($recipientBloodType, $donorBloodType)) {
                echo "<td>Donated</td>";
                // Update the donor count
                $donorCounts[$recipientBloodType]--;
            } else {
                echo "<td>Pending</td>";
            }
        } else {
            echo "<td>Pending</td>";
        }
    
        echo "</tr>";
    }
    
    echo "</table>";

    // Close the database connection
mysqli_close($conn);
        ?>




<br>
    <div class=".Center">
    <a href = '../Main.php'> click here to go back</a>
    </div>
    

    </div>
</div>