<?php 
    require_once "../Database/db_functions.php";

    if (isset($_POST["announcement-box"])) {
        fetchAnnouncements();
    }

    $statusMessage = '';
    if (isset($_SESSION['success'])) {
        $statusMessage = "<p class='success'>" . $_SESSION['success'] . "</p>";
        unset($_SESSION['success']);
    } elseif (isset($_SESSION['error'])) {
        $statusMessage = "<p class='error'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Announcement</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .container {
            max-width: 600px;
            width: 100%;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: #007bff;
        }
        .form-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .form-inline .form-group {
            flex: 1;
            min-width: 200px;
        }
        .btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .form-footer {
            margin-top: 20px;
            text-align: center;
        }
        .form-footer a {
            color: #007bff;
            text-decoration: none;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
        textarea.form-control {
            height: 300px;
            resize: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Admin Announcement</h1>
        </header>
        <form method="post">
            <div class="form-inline">
                <div class="form-group">
                    <label for="Title">Title</label>
                    <input type="text" name="Title" id="Title" class="form-control" placeholder="Title" required>
                </div>
                <div class="form-group">
                    <label for="Organizer">Organizer</label>
                    <input type="text" name="Organizer" id="Organizer" class="form-control" placeholder="Organizer" required>
                </div>
                <div class="form-group">
                    <label for="Date_Event">Date Event</label>
                    <input type="date" name="Date_Event" id="Date_Event" class="form-control" placeholder="Date Event" required>
                </div>
            </div>
            <div class="form-group">
                <label for="Announcement">Announcement</label>
                <textarea name="Announcement" id="Announcement" class="form-control" placeholder="Announcement" required oninput="adjustTextarea(this)"></textarea>
            </div>
            <div class="form-group">
                <input type="submit" name="announcement-box" class="btn" value="Submit">
            </div>
        </form>
        <div class="form-footer">
            <a href='../Main/admin_dashboard.php'>Click here to go back</a>
        </div>
    </div>
    <script>
        function adjustTextarea(el) {
            el.style.height = '300px';
            el.style.height = (el.scrollHeight) + 'px';
        }
    </script>
</body>
</html>