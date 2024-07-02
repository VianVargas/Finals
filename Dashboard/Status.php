<header>
    <h1>Admin:</h1>

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



    $donorCountQuery = "SELECT Blood_type, COUNT(*) as donor_count FROM blood_donors GROUP BY Blood_type";
    $recipientCountQuery = "SELECT Blood_type, COUNT(*) as recipient_count FROM blood_recipients GROUP BY Blood_type";
    
    $donorCountResult = mysqli_query($conn, $donorCountQuery);
    $recipientCountResult = mysqli_query($conn, $recipientCountQuery);
    
    if (!$donorCountResult || !$recipientCountResult) {
        die('Query error: ' . mysqli_error($conn));
    }
    
    // Create associative arrays to store donor and recipient counts
    $donorCounts = [];
    $recipientCounts = [];
    
    while ($row = mysqli_fetch_assoc($donorCountResult)) {
        $donorCounts[$row['Blood_type']] = $row['donor_count'];
    }
    
    while ($row = mysqli_fetch_assoc($recipientCountResult)) {
        $recipientCounts[$row['Blood_type']] = $row['recipient_count'];
    }
    
    // Fetch recipient details
    $recipientQuery = "SELECT * FROM blood_recipients";
    $recipientResult = mysqli_query($conn, $recipientQuery);
    
    if (!$recipientResult) {
        die('Query error: ' . mysqli_error($conn));
    }
    
    // Define blood type compatibility rules
    $compatibility = [
        'A-' => ['A-', 'A+', 'O-', 'O+'],
        'A+' => ['A+', 'AB+'],
        'B-' => ['B-', 'B+', 'O-', 'O+'],
        'B+' => ['B+', 'AB+'],
        'O-' => ['O-'],
        'O+' => ['O+', 'A+', 'B+', 'AB+'],
        'AB-' => ['AB-', 'AB+'],
        'AB+' => ['AB+']
    ];
    
    while ($row = mysqli_fetch_assoc($recipientResult)) {
        $recipientBloodType = strip_tags($row['Blood_type']);
    
        echo "<tr>";
        echo "<td>" . strip_tags($row['Full_Name']) . "</td>";
        echo "<td>" . strip_tags($row['Age']) . "</td>";
        echo "<td>" . strip_tags($row['Birth_Date']) . "</td>";
        echo "<td>" . $recipientBloodType . "</td>";
        echo "<td>" . strip_tags($row['Gender']) . "</td>";
    
        // Determine if the recipient status is 'Donated' or 'Pending'
        $donated = false;
        
        foreach ($compatibility[$recipientBloodType] as $compatibleBloodType) {
            if (isset($donorCounts[$compatibleBloodType]) && $donorCounts[$compatibleBloodType] > 0) {
                echo "<td>Donated</td>";
                // Update the donor count
                $donorCounts[$compatibleBloodType]--;
                $donated = true;
                break;
            }
        }
    
        if (!$donated) {
            echo "<td>Pending</td>";
        }
    
        echo "</tr>";
    }
    ?>




<br>
    <div class=".Center">
    <a href = '../Design/Doctype.php'> click here to go back</a>
    </div>
    

    </div>
</div>