<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once 'authorize.php';

    verify_session("admin");

    $mysqli = db_connect();

    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }

    $admin_id = $_SESSION["user_id"];
    $message = '';

    $stmt = $mysqli->prepare("SELECT user_name, email FROM admin WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if (!$admin) {
        $message = "Error: Admin details not found.";
    }

    // Handle password change
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Verify current password
        $stmt = $mysqli->prepare("SELECT password_hash FROM admin WHERE id = ?");
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin_data = $result->fetch_assoc();

        if (password_verify($current_password, $admin_data['password_hash'])) {
            if ($new_password === $confirm_password) {
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $update_stmt = $mysqli->prepare("UPDATE admin SET password_hash = ? WHERE id = ?");
                $update_stmt->bind_param("si", $new_password_hash, $admin_id);
                
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
    <title>Admin Profile</title>
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
    <?php include ("../Design/navbar_admin.php"); ?>

    <div class="container mt-5">
        <h1>Admin Profile</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($admin): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Admin Details</h5>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($admin['user_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
                <button class="btn btn-primary" onclick="togglePasswordForm()">Change Password</button>
                <form method="POST" action="" class="d-inline">
                    <button type="submit" name="logout" class="btn btn-danger">Logout</button>
                </form>
                <a href="../Main/admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
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
        <?php else: ?>
        <div class="alert alert-danger">
            Admin details not found. Please try logging out and logging in again.
        </div>
        <?php endif; ?>
    </div>

    <?php include ("../Design/footer.php"); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePasswordForm() {
            var form = document.getElementById("passwordForm");
            form.style.display = form.style.display === "none" ? "block" : "none";
        }
    </script>

</body>
</html>