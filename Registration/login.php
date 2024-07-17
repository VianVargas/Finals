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
    <title>Lifestream</title>
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
    </style>
<body style="height: 100%; margin: 0">

    <?php include ("../Design/navbar.php"); ?>

    <div class="d-flex flex-column text-dark">
        <div class="container-fluid d-flex flex-column flex-grow-1">
            <div class="container d-flex flex-column w-100 py-5">
                <div id="carouselExampleIndicators" class="carousel slide">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                            class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="../Images/1.png" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="../Images/2.png" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="../Images/3.png" class="d-block w-100" alt="...">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

                <h1 class="text-dark text-center mt-4">Lifestream</h1>
                <p class="text-dark text-center">
                    Enter your email and password to sign in
                </p>
                <form method="POST" action="#">
                    <div class="container">
                        <div class="d-flex flex-column align-items-center gap-3 mt-4">
                            <div class="form-group d-flex flex-column justify-content-center w-50">
                                <label for="login" class="text-dark font-weight-medium text-center">Username or Email</label>
                                <input type="text" class="form-control bg-white border-2 text-dark w-100" id="login" name="login" value="<?= htmlspecialchars($_POST["login"] ?? "") ?>">
                            </div>

                            <div class="form-group d-flex flex-column justify-content-center w-50">
                                <label for="password" class="text-dark font-weight-medium text-center">Password</label>
                                <input type="password" class="form-control bg-white border-2 text-dark w-100" id="password" name="password">
                            </div>

                            <?php if($is_invalid): ?>
                            <div class="text-danger text-center"><em>Invalid Login</em></div>
                            <?php endif; ?>

                            <button class="btn btn-danger text-dark font-weight-bold rounded-lg px-3 py-2" type="submit">Sign in</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include ("../Design/footer.php");?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
