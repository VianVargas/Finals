<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../Registration/authorize.php';
verify_session('user'); 

$user_name = htmlspecialchars($_SESSION["user_name"]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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
        }
        .container {
            max-width: 1500px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .welcome-message {
            text-align: center;
            margin-bottom: 20px;
        }
        .dashboard-links {
            list-style-type: none;
            padding: 0;
            display: flex;
            justify-content: space-around;
        }
        .dashboard-links li {
            flex: 1;
            margin: 0 10px;
        }
        .dashboard-links a {
            display: block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .dashboard-links a:hover {
            background-color: #0056b3;
        }
        .logout-link {
            display: block;
            text-align: left;
            margin-top: 20px;
        }
        .logout-link a {
            color: #007bff;
            text-decoration: none;
        }
        .logout-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Dashboard</h1>
        <div class="welcome-message">
            <p>Welcome, <?php echo $user_name; ?>!</p>
        </div>
        <ul class="dashboard-links">
            <li><a href="../Dashboard/Add.php">Donate Blood</a></li>
            <li><a href="../Dashboard/View_Donation.php">View My Donation Details</a></li>
            <li><a href="../Dashboard/Search.php">Search Recipients</a></li>
            <li><a href="../Dashboard/View_Announcement.php">Announcement View</a></li>
        </ul>
        <div class="logout-link">
            <a href="../Registration/logout.php" id="logout-link">Logout</a>
        </div>
        </div>

        <script>
            document.getElementById('logout-link').addEventListener('click', function(event) {
                event.preventDefault(); 
                
                if (confirm('Are you sure you want to logout?')) {
                    window.location.href = this.href;
                }
            });
        </script>
    </div>
</body>
</html>


<!-- Chart Blood and Gender distrubution-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-wrapper {
            display: flex;
            margin: 5% auto;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .chart-container {
            flex: 1;
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .chart-container2 {
            flex: 1;
            max-width: 350px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once '../Registration/authorize.php';
    verify_session('user');
    $user_name = htmlspecialchars($_SESSION["user_name"]);

    // Database connection
    include "../Database/db_connect.php";

    // Fetch gender distribution
    $gender_query = "SELECT Gender, COUNT(*) as count FROM blood_donors GROUP BY Gender";
    $gender_result = $conn->query($gender_query);
    $gender_data = [];
    while ($row = $gender_result->fetch_assoc()) {
        $gender_data[] = $row;
    }

    // Fetch blood type distribution
    $blood_type_query = "SELECT Blood_type, COUNT(*) as count FROM blood_donors GROUP BY Blood_type";
    $blood_type_result = $conn->query($blood_type_query);
    $blood_type_data = [];
    $total_count = 0;
    while ($row = $blood_type_result->fetch_assoc()) {
        $blood_type_data[] = $row;
        $total_count += $row['count']; 
    }

    // Convert data to JSON for use in JavaScript
    $blood_type_json = json_encode($blood_type_data);



    // Convert data to JSON for use in JavaScript
    $gender_json = json_encode($gender_data);
    $blood_type_json = json_encode($blood_type_data);
    ?>

   <div class="chart-wrapper">
        <div class="chart-container2">
            <canvas id="genderChart"></canvas>
        </div>

        <div class="chart-container">
            <canvas id="bloodTypeChart"></canvas>
        </div>
    
        
 


    <script>
        // Gender Distribution Chart
        var genderCtx = document.getElementById('genderChart').getContext('2d');
        var genderData = <?php echo $gender_json; ?>;
        
        new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: genderData.map(item => item.Gender),
                datasets: [{
                    label: 'Gender Distribution',
                    data: genderData.map(item => item.count),
                    backgroundColor: ['rgba(54, 162, 235, 0.8)', 'rgba(255, 99, 132, 0.8)', 'rgba(45, 26, 132, 0.8)'],
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        
    var bloodTypeCtx = document.getElementById('bloodTypeChart').getContext('2d');
    var bloodTypeData = <?php echo $blood_type_json; ?>;
    var totalBloodTypes = <?php echo $total_count; ?>; // Total count from PHP

new Chart(bloodTypeCtx, {
    type: 'bar',
    data: {
        labels: bloodTypeData.map(item => item.Blood_type),
        datasets: [{
            label: 'Blood Type Distribution',
            data: bloodTypeData.map(item => item.count),
            backgroundColor: [
          "rgba(75, 192, 192, 0.8)",
          "rgba(100, 192, 152, 0.8)",
          "rgba(100, 75, 255, 0.8)",
          "rgba(100, 75, 50, 0.8)",
          "rgba(200, 100, 50, 0.8)",
          "rgba(150, 192, 75, 0.8)",
          "rgba(192, 50, 100, 0.8)",
          "rgba(192, 100, 150, 0.8)"
        ],
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        var label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += context.raw.toLocaleString();
                        return label;
                    },
                    afterLabel: function(context) {
                        if (context.dataset.label === 'Blood Type Distribution') {
                            return 'Total: ' + totalBloodTypes.toLocaleString();
                        }
                    }
                }
            }
        }
    }
});
        

        
</script>
</body>
</html>


