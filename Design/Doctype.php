<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://www.apple.com/wss/fonts?family=SF+Pro&amp;v=2" type="text/css">
</head>
  <body>

    <header>
        <h1>Dashboard</h1>
    </header>

    <?php if (isset($_SESSION["user_id"])):?>
        <p>You are logged in.</p>

        <p><a href = "../Registration/logout.php">Log Out</a></p>
    <?php else: ?>
        <p><a href = "../Registration/login.php">Log In</a> or  <a href = "Registration/signup.php">signup</a></p>
    <?php endif; ?>

        <main>
        <a href="../Dashboard/add.php">Donoate Blood</a>
        <br>
        <a href="../Dashboard/addR.php">Add Recipients Details</a>
        <br>
        <a href="../Dashboard/Search.php">Search Blood recipients</a>
        <br>
        <a href="../Dashboard/Announcement.php">Announcement</a>
        <br>
        <a href="../Dashboard/Status.php">Donor Status</a>
        <br>
        <a href="../Dashboard/View.php">View Blood Donors</a>
        <br>
        </main>

 </body>
</html>