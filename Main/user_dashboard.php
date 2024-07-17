<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../Registration/authorize.php';
    verify_session('user'); 

    $user_name = htmlspecialchars($_SESSION["user_name"]);

    include "../Database/db_connect.php";

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

    // Fetch announcements
    $sql = "SELECT Title, Announcement, Organizer, Date_Event FROM announcements ORDER BY Date_Event DESC";
    $announcement_result = mysqli_query($conn, $sql);

    if (!$announcement_result) {
        die('Error: ' . mysqli_error($conn));
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>User Dashboard</title>
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
        footer {
            flex-shrink: 0;
        }
        .chart-wrapper {
            display: flex;
            margin: 5% auto;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }
        .chart-container {
            width: 100%;
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .chart-header {
            text-align: center;
            margin-bottom: 20px;
            color: #dc3545;
            font-weight: bold;
        }
        .announcement-container {
            max-height: 400px;
            overflow-y: auto;
            padding: 10px;
        }
        .announcement {
            background-color: #fff;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            position: relative;
            word-wrap: break-word;
        }
        .announcement h3 {
            font-size: 20px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .announcement p {
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .announcement .meta {
            font-size: 12px;
            color: #777;
        }
        .meta-item {
            margin-right: 10px;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .card-title {
            color: #fff;
            font-weight: bold;
        }
        .btn-link {
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }
        .btn-link:hover {
            color: #f8f9fa !important;
        }
        h2, h3 {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <?php include ("../Design/navbar_user.php"); ?>
    
    <div class="content-wrapper">
        <div class="container-fluid mt-3 px-5 py-3">
            <h2 class="mb-4">Welcome, <?php echo $user_name; ?>!</h2>
            <div class="card bg-light py-2 px-3 mb-4">
                <p class="card-title fs-5 text-dark">User Dashboard</p>
            </div>

            <div class="row">
                <div class="col-lg-12 col-sm-12 mb-4">
                    <div class="card bg-danger">
                        <div class="card-body">
                            <h3 class="card-title">Announcements</h3>
                            <div class="announcement-container">
                                <?php if (mysqli_num_rows($announcement_result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($announcement_result)): ?>
                                        <div class="announcement">
                                            <h3><?php echo htmlspecialchars($row['Title']); ?></h3>
                                            <p><?php echo nl2br(htmlspecialchars($row['Announcement'])); ?></p>
                                            <div class="meta">
                                                <span class="meta-item"><strong>Organizer:</strong> <?php echo htmlspecialchars($row['Organizer']); ?></span>
                                                <span class="meta-item"><strong>Date Event:</strong> <?php echo htmlspecialchars($row['Date_Event']); ?></span>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p>No announcements found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12 mb-4">
                    <div class="card bg-danger">
                        <div class="card-body">
                            <h3 class="card-title">Donate Blood</h3>
                            <div class="d-flex justify-content-end mt-3">
                                <button class="btn btn-link text-white" onclick="window.location.href='../Dashboard/Add.php'">
                                    Donor Donation <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12 mb-4">
                    <div class="card bg-danger">
                        <div class="card-body">
                            <h3 class="card-title">My Donations</h3>
                            <div class="d-flex justify-content-end mt-3">
                                <button class="btn btn-link text-white" onclick="window.location.href='../Dashboard/View_Donation.php'">
                                    View my donations <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-sm-12 mb-4">
                    <div class="card bg-danger">
                        <div class="card-body">
                            <h3 class="card-title">Search Recipients</h3>
                            <div class="d-flex justify-content-end mt-3">
                                <button class="btn btn-link text-white" onclick="window.location.href='../Dashboard/Search.php'">
                                    Search Blood Recipients <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart section -->
            <div class="chart-wrapper">
                <h2 class="chart-header">Blood Type Distribution</h2>
                <div class="chart-container">
                    <canvas id="bloodTypeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <?php include ("../Design/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

    <script>
        // Blood Type Distribution Chart
        var bloodTypeCtx = document.getElementById('bloodTypeChart').getContext('2d');
        var bloodTypeData = <?php echo $blood_type_json; ?>;
        var totalBloodTypes = <?php echo $total_count; ?>;

        new Chart(bloodTypeCtx, {
            type: 'bar',
            data: {
                labels: bloodTypeData.map(item => item.Blood_type),
                datasets: [{
                    label: 'Number of Donors',
                    data: bloodTypeData.map(item => item.count),
                    backgroundColor: [
                        "rgba(220, 53, 69, 0.8)",
                        "rgba(220, 53, 69, 0.7)",
                        "rgba(220, 53, 69, 0.6)",
                        "rgba(220, 53, 69, 0.5)",
                        "rgba(220, 53, 69, 0.4)",
                        "rgba(220, 53, 69, 0.3)",
                        "rgba(220, 53, 69, 0.2)",
                        "rgba(220, 53, 69, 0.1)"
                    ],
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Donors'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Blood Type'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = 'Number of Donors: ';
                                label += context.raw.toLocaleString();
                                return label;
                            },
                            afterLabel: function(context) {
                                return 'Total Donors: ' + totalBloodTypes.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>

</body>
</html>