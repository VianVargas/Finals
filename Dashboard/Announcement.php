<?php 
    require_once "../Database/db_functions.php";

    if (isset($_POST["announcement-box"])) {
        fetchAnnouncements();
    }

    $statusMessage = '';
    if (isset($_SESSION['success'])) {
        $statusMessage = "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
        unset($_SESSION['success']);
    } elseif (isset($_SESSION['error'])) {
        $statusMessage = "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
        unset($_SESSION['error']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Announcement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
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
        .footer {
            flex-shrink: 0;
        }
        .container {
            max-width: 90%;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        .btn-primary {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover, .btn-secondary:focus {
            background-color: #5a6268;
            border-color: #545b62;
        }
        h1 {
            color: #dc3545;
            font-weight: bold;
        }
        textarea.form-control {
            height: 300px;
            resize: vertical;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <?php include ("../Design/navbar_admin.php"); ?>
        
        <div class="container">
            <h1 class="mb-4">Admin Announcement</h1>

            <form method="post">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="Title" class="form-label">Title</label>
                        <input type="text" name="Title" id="Title" class="form-control" placeholder="Title" required>
                    </div>
                    <div class="col-md-4">
                        <label for="Organizer" class="form-label">Organizer</label>
                        <input type="text" name="Organizer" id="Organizer" class="form-control" placeholder="Organizer" required>
                    </div>
                    <div class="col-md-4">
                        <label for="Date_Event" class="form-label">Date Event</label>
                        <input type="date" name="Date_Event" id="Date_Event" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="Announcement" class="form-label">Announcement</label>
                    <textarea name="Announcement" id="Announcement" class="form-control" placeholder="Announcement" required></textarea>
                </div>

                <?php echo $statusMessage; ?>

                <div class="mb-3">
                    <button type="submit" name="announcement-box" class="btn btn-primary">Submit</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <a href="../Main/admin_dashboard.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <?php include ("../Design/footer.php"); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>
</html>