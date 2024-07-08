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



// Fetch donor counts
$donorCountQuery = "SELECT Blood_type, COUNT(*) as donor_count FROM blood_donors GROUP BY Blood_type";
$donorCountResult = mysqli_query($conn, $donorCountQuery);

// Fetch recipient counts
$recipientCountQuery = "SELECT Blood_type, COUNT(*) as recipient_count FROM blood_recipients GROUP BY Blood_type";
$recipientCountResult = mysqli_query($conn, $recipientCountQuery);

// Error handling for queries
if (!$donorCountResult || !$recipientCountResult) {
    die('Query error: ' . mysqli_error($conn));
}

// Arrays to store donor and recipient counts
$donorCounts = [];
$recipientCounts = [];

// Fetch donor counts and store in associative array
while ($row = mysqli_fetch_assoc($donorCountResult)) {
    $donorCounts[$row['Blood_type']] = $row['donor_count'];
}

// Fetch recipient counts and store in associative array
while ($row = mysqli_fetch_assoc($recipientCountResult)) {
    $recipientCounts[$row['Blood_type']] = $row['recipient_count'];
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

// Fetch recipient details
$recipientQuery = "SELECT * FROM blood_recipients";
$recipientResult = mysqli_query($conn, $recipientQuery);

// Error handling for recipient query
if (!$recipientResult) {
    die('Query error: ' . mysqli_error($conn));
}

// Loop through recipients
while ($row = mysqli_fetch_assoc($recipientResult)) {
    $recipientBloodType = strip_tags($row['Blood_type']);

    echo "<tr>";
    echo "<td>" . strip_tags($row['Full_Name']) . "</td>";
    echo "<td>" . strip_tags($row['Age']) . "</td>";
    echo "<td>" . strip_tags($row['Birth_Date']) . "</td>";
    echo "<td>" . $recipientBloodType . "</td>";
    echo "<td>" . strip_tags($row['Gender']) . "</td>";

    // Priority selection form
    echo "<td>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='recipient_id' value='" . $row['Recipients_ID'] . "'>";
    echo "<button type='submit' name='submit_priority'>Set Priority</button>";
    echo "</form>";
    echo "</td>";

    // Determine recipient status based on priority setting
    if (isset($_POST['submit_priority']) && $_POST['recipient_id'] == $row['Recipients_ID']) {
        $compatibleBloodTypes = $compatibility[$recipientBloodType];
        $donated = false;

        // Check for available donors
        foreach ($compatibleBloodTypes as $compatibleBloodType) {
            if (isset($donorCounts[$compatibleBloodType]) && $donorCounts[$compatibleBloodType] < $recipientBloodType) {
                echo "<td>Donated</td>";
                // Update donor count and mark as donated
                $donorCounts[$compatibleBloodType]--;
                $donated = true;
                break; // Exit loop once a donor is found
            }
        }

        // If no donor is found, mark as pending
        if (!$donated) {
            echo "<td>Pending</td>";
        }
    } else {
        // Default status if no priority is set
        echo "<td>Pending</td>";
    }

    echo "</tr>";
}

// Close database connection
mysqli_close($conn);
?>



<br>
    <div class=".Center">
    <a href = '../Main.php'> click here to go back</a>
    </div>
    

    </div>
</div>