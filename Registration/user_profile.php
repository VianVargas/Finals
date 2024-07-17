<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once 'authorize.php';

    verify_session();

    $mysqli = db_connect();

    $user_id = $_SESSION["user_id"];
    $message = '';

    $stmt = $mysqli->prepare("SELECT user_name, email FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Handle password change
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Verify current password
        $stmt = $mysqli->prepare("SELECT password_hash FROM user WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();

        if (password_verify($current_password, $user_data['password_hash'])) {
            if ($new_password === $confirm_password) {
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $update_stmt = $mysqli->prepare("UPDATE user SET password_hash = ? WHERE id = ?");
                $update_stmt->bind_param("si", $new_password_hash, $user_id);
                
                if ($update_stmt->execute()) {
                    $message = "Password updated successfully!";
                } else {
                    $message = "Error updating password. Please try again.";
                }
            } else {
                $message = "New passwords do not match.";
            }
        } else {
            $message = "Current password is incorrect.";
        }
    }

    if (isset($_POST['logout'])) {
        logout();
        header("Location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
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
</style>
<body>
    <?php include ("../Design/navbar_user.php"); ?>

    <div class="container mt-5">
        <h1>User Profile</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">User Details</h5>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['user_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <button class="btn btn-primary" onclick="togglePasswordForm()">Change Password</button>
                <form method="POST" action="" class="d-inline">
                    <button type="submit" name="logout" class="btn btn-danger">Logout</button>
                </form>
                <a href="../Main/user_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>

        <div class="card" id="passwordForm" style="display: none;">
            <div class="card-body">
                <h5 class="card-title">Change Password</h5>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>

    <?php include ("../Design/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePasswordForm() {
            var form = document.getElementById("passwordForm");
            if (form.style.display === "none") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }
    </script>
    
</body>
</html>