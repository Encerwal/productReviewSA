<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        //$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=emoticart;user=postgres;password=102475");
        $host = $_ENV['DATABASE_HOST'];
        $db_password = $_ENV['DATABASE_PASSWORD'];

        $pdo = new PDO("pgsql:host=$host;port=5432;dbname=emoticart;user=emoticart;password=$db_password");

        // Check if the username or email already exists in the database
        $sql = "SELECT * FROM users WHERE email = :email OR username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email, 'username' => $username]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            if ($existingUser['email'] === $email) {
                $error_message = "An account with this email already exists.";
            } elseif ($existingUser['username'] === $username) {
                $error_message = "An account with this username already exists.";
            }
        } else {
            // Insert new user data into the database
            $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password]);

            // Redirect to the login page after successful registration
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

include('header.php');
?>

    <style>
        #container-login {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 500px;
            
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"],input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>


<section id="hero" class="hero section">
    <div class="container" id="container-login">
        <h2>Register</h2>
        <?php if (!empty($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required> 
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button class="btn-get-started" id="btn-analyze" type="submit">Sign Up</button>
        </form>
    </div>
</section>

<?php
include('footer.php');
?>
