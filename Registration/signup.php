<?php
require_once 'authorize.php';

$errors = [];
$signup_success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['user_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirmation = $_POST['password_confirmation'] ?? '';

    if (empty($user_name)) {
        $errors[] = "Username is required";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid Email is required";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }
    if (!preg_match("/[a-z]/i", $password)) {
        $errors[] = "Password must contain at least one letter";
    }
    if (!preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must contain at least one number";
    }
    if ($password !== $password_confirmation) {
        $errors[] = "Passwords do not match";
    }

    if (empty($errors)) {
        try {
            if (signup($user_name, $email, $password)) {
                $signup_success = true;
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h1>Sign Up</h1>

    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($signup_success): ?>
        <p>Sign Up Successful!</p>
        <p><a href="../Registration/login.php">Click here to login</a></p>
    <?php else: ?>
        <form method="POST" novalidate>
            <div>
                <label for="user_name">Username</label>
                <input type="text" name="user_name" id="user_name" value="<?= htmlspecialchars($_POST["user_name"] ?? "") ?>">
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
            </div>
            <div>
                <label for="password_confirmation">Repeat Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation">
            </div>
            
            <button>Sign Up</button>
            <p>Already have an account? <a href="../Registration/login.php">Click here to login</a></p>
        </form>
    <?php endif; ?>
</body>
</html>