<?php 
    include "../Database/db_login.php";
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
<h1>Login</h1>     
    <form method="POST">
        <label for="email">Username or Email</label>
        <input type="text" name="login" id="login"
                value="<?= htmlspecialchars($_POST["login"] ?? "")?>">

        <label for="password">Password</label>
        <input type="password" name="password" id="password">

        <?php if($is_invalid): ?>
        <em>Invalid Login<br></em>
        <?php endif; ?>

        <button>Log In</button>

        <p><a href="signup.php">Don't have an Account?</a></p>
    </form>