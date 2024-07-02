<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
<h1>Sign Up</h1>
    <form method="POST" novalidate>
        <div>
            <label for="name">Username</label>
            <input type="text" name="user_name" id="user_name"
                value="<?= htmlspecialchars($_POST["user_name"] ?? "")?>">

        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email"
            value="<?= htmlspecialchars($_POST["email"] ?? "")?>"> 
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
        </div>

        <button>Sign Up</button>

        <?php include "../Database/db_signup.php"; ?>
    </form>

    <?php if (isset($signup_success) && $signup_success): ?>
        <p>Sign Up Successful!</p>
        <p><a href="login.php">Click here to login</a></p>
    <?php endif; ?>