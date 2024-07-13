<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'authorize.php';

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (login($_POST["login"], $_POST["password"])) {
        if ($_SESSION["user_role"] == "admin") {
            header("Location: ../Main/admin_dashboard.php");
        } else {
            header("Location: ../Main/user_dashboard.php");
        }
        exit;
    }
    $is_invalid = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="initialStyle.css">
</head>
<body>
    <div class="container">
    <h1>Login</h1>     
    <form method="POST">
        <label for="login">Username or Email</label>
        <input type="text" name="login" id="login" value="<?= htmlspecialchars($_POST["login"] ?? "") ?>">

        <label for="password">Password</label>
        <input type="password" name="password" id="password">

        <?php if($is_invalid): ?>
        <em>Invalid Login<br></em>
        <?php endif; ?>

        <button>Log In</button>

        <p>Don't have an Account? <a href="signup.php">Register here</a></p>
    </form>
    </div>
</body>
</html>