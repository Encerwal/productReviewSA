<?php
require_once 'auth.php';

// Check if the user is already logged in
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit(); 
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Connect to the PostgreSQL database
        $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=emoticart;user=postgres;password=102475");

        // Prepare a statement to fetch user details based on the username
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // If the user is found and the password is correct, start a session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect the user to dashboard.php after successful login
            header("Location: manage_products.php");
            exit(); // Ensure no further code is executed
        } else {
            $error_message = "Invalid username or password!";
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . htmlspecialchars($e->getMessage());
    }
}
?>

<?php
// Include the header after processing logic
include('header.php');
?>

<style>

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
 
</style>

<section id="hero" class="hero section">
    <div class="container" id="container-login">
        <h2>Login</h2>

        <!-- Display error message if it exists -->
        <?php if (!empty($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

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
// Include the footer
include('footer.php');
?>
