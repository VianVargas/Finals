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
    <title>Lifestream</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link rel="stylesheet" as="style" onload="this.rel='stylesheet'" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
    <style>
        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        footer {
            margin-top: auto;
            width: 100%;
        }

        .form-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
<body style="height: 100%">
    <?php include ("../Design/navbar.php"); ?>

    <div class="d-flex flex-column min-vh-100 text-dark" style="font-family: Inter, 'Noto Sans', sans-serif;">
        <div class="container-fluid d-flex flex-column flex-grow-1 form-container">
            <div class="container d-flex flex-column w-100 py-5">
                <h2 class="text-center" style="font-size: 28px;">
                    Join Lifestream
                </h2>
                
                <?php if ($signup_success): ?>
                    <div class="alert alert-success text-center" role="alert">
                        Sign Up Successful! <a href="../Registration/login.php" class="alert-link">Click here to login</a>.
                    </div>
                <?php else: ?>
                    <form method="POST" action="#">
                        <div class="mt-4">
                            <div class="row d-flex justify-content-center">
                                <div class="form-group col-md-6">
                                    <label for="user_name" class="form-label">
                                        Username
                                    </label>
                                    <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Username"
                                        value="<?= htmlspecialchars($_POST["user_name"] ?? "") ?>">
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="form-group col-md-6">
                                    <label for="email" class="form-label">
                                        Email
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                        value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="form-group col-md-6 ">
                                    <label for="password" class="form-label">
                                        Password
                                    </label>
                                    <input type="password" class="form-control" id="password" name="password" />
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="form-group col-md-6 w-50">
                                    <label for="password_confirmation" class="form-label">
                                        Repeat Password
                                    </label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" />
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center mt-3">
                                <div class="form-group form-check col-md-6 ">
                                    <input type="checkbox" class="form-check-input" id="termsCheck" />
                                    <label class="form-check-label" for="termsCheck">
                                        I have read and accept the terms of service and privacy
                                        policy.
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                <button class="btn btn-danger text-white font-weight-bold rounded-lg px-4 py-2"
                                    style="min-width: 84px;" type="submit">
                                    Sign up
                                </button>
                            </div>

                            <?php if (!empty($errors)): ?>
                                <div class="row d-flex justify-content-center mt-3">
                                    <div class="alert alert-danger col-md-6" role="alert">
                                        <ul class="mb-0">
                                            <?php foreach ($errors as $error): ?>
                                                <li><?= htmlspecialchars($error) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include ("../Design/footer.php");?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
</body>
</html>
