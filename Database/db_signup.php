<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $valid = true;
    $signup_success = false;    

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if (empty($_POST["user_name"])){
            die("Username is required");
        }
    
        if (! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
            die("Valid Email is required");
        }
    
        if (strlen($_POST["password"] < 8)) {
            die("Password must be at least 8 characters");
        }
    
        if (! preg_match("/[a-z]/i", $_POST["password"])){
            die("Password must contain at least one letter");
        }
    
        if (! preg_match("/[0-9]/", $_POST["password"])){
            die("Password must contain at least one number");
        }    

        if($valid){
            $user_name = $_POST['user_name'];
            $email = $_POST['email'];
            $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
   
            $mysqli = require __DIR__ . "/db_connect.php";
        
            $sql = "INSERT INTO user (user_name, email, password_hash)
                    VALUES (?, ?, ?)";
        
            $stmt = $mysqli->stmt_init();
        
            if (! $stmt->prepare($sql)){
                die("SQL error: " . $mysqli->error);
            }
        
            $stmt->bind_param("sss", $user_name, $email, $password_hash);

            try {
                if ($stmt->execute()){
                    $signup_success = true; 
                }
            } catch (mysqli_sql_exception $e){
                // Check if the error message contains 'Duplicate entry'
                if (strpos($e->getMessage(), 'Duplicate entry') !== false){
                    // Check if the duplicate is for 'email' or 'username'
                    if (strpos($e->getMessage(), 'for key \'email\'') !== false) {
                        echo "Email is already taken.";
                    } else if (strpos($e->getMessage(), 'for key \'username\'') !== false) {
                        echo "Username is already taken.";
                    }
                } else {
                    // For any other SQL errors, print a generic error message
                    echo "Error: " . $e->getMessage();
                }
            }
            
        }

    }

?>