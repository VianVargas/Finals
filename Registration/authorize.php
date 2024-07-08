<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function db_connect() {
    $mysqli = require __DIR__ . '/../Database/db_connect.php';
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    return $mysqli;
}

function login($login, $password) {
    $mysqli = db_connect();
    
    // Check user table
    $stmt = $mysqli->prepare("SELECT * FROM user WHERE email = ? OR user_name = ?");
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["password_hash"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_role"] = "user";
        $_SESSION["user_name"] = $user["user_name"];
        $_SESSION["user_email"] = $user["email"];
        return true;
    }

    // Check admin table
    $stmt = $mysqli->prepare("SELECT * FROM admin WHERE email = ? OR user_name = ?");
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin["password_hash"])) {
        $_SESSION["user_id"] = $admin["id"];
        $_SESSION["user_role"] = "admin";
        $_SESSION["user_name"] = $admin["user_name"];
        return true;
    }

    return false;
}

function signup($user_name, $email, $password) {
    $mysqli = db_connect();
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO user (user_name, email, password_hash) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sss", $user_name, $email, $password_hash);
    
    try {
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            if (strpos($e->getMessage(), 'for key \'email\'') !== false) {
                throw new Exception("Email is already taken.");
            } else if (strpos($e->getMessage(), 'for key \'username\'') !== false) {
                throw new Exception("Username is already taken.");
            }
        }
        throw $e;
    }
}

function logout() {
    session_unset();
    session_destroy();
}

function verify_session($required_role = null) {
    if (!isset($_SESSION["user_id"])) {
        header("Location: ../Registration/login.php");
        exit;
    }

    if ($required_role !== null && $_SESSION["user_role"] !== $required_role) {
        header("Location: ../Registration/unauthorized.php");
        exit;
    }
}
?>