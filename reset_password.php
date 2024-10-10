<?php
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        //$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=emoticart;user=postgres;password=102475");
        $host = $_ENV['DATABASE_HOST'];
        $password = $_ENV['DATABASE_PASSWORD'];

        $pdo = new PDO("pgsql:host=$host;port=5432;dbname=emoticart;user=emoticart;password=$password");

        // Check if the token exists and is still valid
        $sql = "SELECT * FROM password_resets WHERE token = :token AND expires_at >= :current_time";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['token' => $token, 'current_time' => date("U")]);
        $reset = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($reset) {
            // Token is valid, update the user's password
            $sql = "UPDATE users SET password = :new_password WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['new_password' => $new_password, 'email' => $reset['email']]);

            // Delete the token after successful password reset
            $sql = "DELETE FROM password_resets WHERE token = :token";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['token' => $token]);

            // Show a custom modal with countdown and redirect
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    const overlay = document.getElementById('overlay-results');
                    const modal = document.getElementById('deleteModal');
                    overlay.style.display = 'block';
                    modal.style.display = 'block';

                    const countdownElement = document.getElementById('countdown');
                    let timeLeft = 5;

                    // Update the countdown every second
                    const countdownInterval = setInterval(function() {
                        if (timeLeft <= 0) {
                            clearInterval(countdownInterval);
                            window.location.href = 'login.php';
                        } else {
                            countdownElement.innerHTML = timeLeft + ' seconds';
                            timeLeft -= 1;
                        }
                    }, 1000); // 1000 milliseconds = 1 second
                });
            </script>";
        } else {
            $error_message = "Invalid or expired token.";
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
include('header.php');
?>
<style>
    h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

</style>

<main class="main">
    <section id="hero" class="hero section">
        <div class="container" id="container-login">
            <h2>Reset Password</h2>
            <!-- Display error message if it exists -->
            <?php if (!empty($error_message)): ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="post">
                <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
                <input type="password" name="password" placeholder="Enter your new password" required>
                <button class="btn-get-started" id="btn-analyze"  type="submit">Reset</button>
            </form>
        </div>
    </section>

    <!-- Success Modal -->
    <div id="overlay-results" class="overlay-results"></div>
    <div id="deleteModal" class="deleteModal">
        <div class="modal-content" id="modalContent">
            <h2>Password reset successful!</h2>
            <p>You will be redirected to the login page in <span id="countdown">5 seconds</span>.</p>
        </div>
    </div>
</main> 
<?php
include('footer.php');
?>
