<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    $is_invalid = false;

    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        $mysqli = require __DIR__ . "/../Database/db_connect.php";

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        $login = $_POST["login"];
        $password = $_POST["password"];
    
        $stmt = $mysqli->prepare("SELECT * FROM user WHERE email = ? OR user_name = ?");
        $stmt->bind_param("ss", $login, $login);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($password, $user["password_hash"])) {
                session_start();

                $_SESSION["user_id"] = $user["id"];
                
                header("Location: ../Design/Doctype.php");
                exit();
            }
        }
        $is_invalid = true;
    }
?>

