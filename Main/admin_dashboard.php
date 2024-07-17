<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../Registration/authorize.php';
    verify_session('admin');

    $user_name = htmlspecialchars($_SESSION["user_name"]);

    include '../Database/db_connect.php';

    // Fetch donor data
    $sql_donors = "SELECT Donors_ID, Full_Name, Age, Birth_Date, Blood_Type, Gender, Collection_Date FROM blood_donors";
    $result_donors = mysqli_query($conn, $sql_donors);

    // Fetch recipient data
    $sql_recipients = "SELECT Recipients_ID, Full_Name, Age, Birth_Date, Blood_type, Gender FROM blood_recipients";
    $result_recipients = mysqli_query($conn, $sql_recipients);
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
    <title>Admin Dashboard</title>
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
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
        .table {
            background-color: #fff;
        }
        .table th {
            position: sticky;
            top: 0;
            background-color: #dc3545;
            color: #fff;
        }
        .btn-edit, .btn-delete {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
        .btn-edit {
            color: #fff;
            background-color: #ffc107;
            border-color: #ffc107;
        }
        .btn-delete {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-outline-danger.active {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>
    <?php include ("../Design/navbar_admin.php"); ?>

    <div class="content-wrapper">
        <div class="container-fluid mt-3 px-5 py-3">
            <h2 class="mb-4">Welcome back, Admin <?php echo $user_name; ?>!</h2>
            <div class="card bg-light py-2 px-3 mb-4">
                <p class="card-title fs-5 text-dark">Admin Dashboard</p>
            </div>

            <div class="row">
                <div class="col-lg-6 col-sm-12 mb-4">
                    <div class="card bg-danger">
                        <div class="card-body">
                            <h3 class="card-title">Recipients</h3>
                            <div class="d-flex justify-content-end mt-3">
                                <button class="btn btn-link text-white" onclick="window.location.href='../Dashboard/AddR.php'">
                                    Add Recipient Details <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12 mb-4">
                    <div class="card bg-danger">
                        <div class="card-body">
                            <h3 class="card-title">Announcements</h3>
                            <div class="d-flex justify-content-end mt-3">
                                <button class="btn btn-link text-white" onclick="window.location.href='../Dashboard/Announcement.php'">
                                    Add Announcement <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card bg-danger">
                        <div class="card-body">
                            <h3 class="card-title">Status</h3>
                            <div class="d-flex justify-content-end mt-3">
                                <button class="btn btn-link text-white" onclick="window.location.href='../Dashboard/Status.php'">
                                    View Status Details <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-light mb-4">
                <div class="card-body">
                    <h3 class="card-title text-dark">Blood Collection</h3>
                    <div class="btn-group mb-3" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-outline-danger active" id="donors-btn" onclick="showTable('donors')">Donors</button>
                        <button type="button" class="btn btn-outline-danger" id="recipients-btn" onclick="showTable('recipients')">Recipients</button>
                    </div>
                    <div id="donors-table" class="table-container">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Age</th>
                                    <th>Birth Date</th>
                                    <th>Blood Type</th>
                                    <th>Gender</th>
                                    <th>Collection Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_array($result_donors)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['Full_Name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Age']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Birth_Date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Blood_Type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Gender']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Collection_Date']); ?></td>
                                    <td>
                                        <a href='../Dashboard/Edit.php?id=<?php echo $row['Donors_ID']; ?>' class="btn btn-edit">Edit</a>
                                        <a href='../Dashboard/Delete.php?id=<?php echo $row['Donors_ID']; ?>' class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="recipients-table" class="table-container" style="display: none;">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Age</th>
                                    <th>Birth Date</th>
                                    <th>Blood Type</th>
                                    <th>Gender</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_array($result_recipients)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['Full_Name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Age']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Birth_Date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Blood_type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Gender']); ?></td>
                                    <td>
                                        <a href='../Dashboard/EditR.php?id=<?php echo $row['Recipients_ID']; ?>' class="btn btn-edit">Edit</a>
                                        <a href='../Dashboard/DeleteR.php?id=<?php echo $row['Recipients_ID']; ?>' class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include ("../Design/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
    function showTable(tableId) {
        // Hide all tables
        document.getElementById('donors-table').style.display = 'none';
        document.getElementById('recipients-table').style.display = 'none';
        
        // Show the selected table
        document.getElementById(tableId + '-table').style.display = 'block';
        
        document.getElementById('donors-btn').classList.remove('active');
        document.getElementById('recipients-btn').classList.remove('active');
        document.getElementById(tableId + '-btn').classList.add('active');
    }

    // Initialize the view with the donors table
    showTable('donors');
    </script>

</body>
</html>