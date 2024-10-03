<?php
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
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
      
        .error {
            color: red;
            text-align: center;
        }
        .success {
            color: green;
            text-align: center;
        }
    </style>


    <section id="hero" class="hero section">
    <div class="container" id="container-login">
        <h2>Login</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button class="btn-get-started" id="btn-analyze" type="submit">Login</button>
        </form>

        <!-- Link to Forgot Password Page -->
        <div class="forgot-password-link">
            <p><a href="forgot_password.php">Forgot password?</a></p>
        </div>

        <!-- Link to Forgot Username Page -->
        <div class="forgot-username-link">
            <p><a href="forgot_username.php">Forgot username?</a></p>
        </div>

        <!-- Link to Registration Page -->
        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
    </section>
<?php
include('footer.php');
?>
