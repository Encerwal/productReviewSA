<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
// UNCOMMENT THIS FOR LOCAL PHP MAILER
//$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
//$dotenv->load();
$error_message = '';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    try {
        //$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=emoticart;user=postgres;password=102475");
        $host = $_ENV['DATABASE_HOST'];
        $password = $_ENV['DATABASE_PASSWORD'];

        $pdo = new PDO("pgsql:host=$host;port=5432;dbname=emoticart;user=emoticart;password=$password");
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Generate a secure token
            $token = bin2hex(random_bytes(50));
            $expires = date("U") + 1800;

            // Store the token in the database
            $sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $email, 'token' => $token, 'expires' => $expires]);

            // Create a reset link
            $reset_link = "https://emoticart.onrender.com/reset_password.php?token=" . $token;

            // Send the email using PHPMailer
            $mail = new PHPMailer(true);
            
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = $_ENV['SMTP_USERNAME'];
                $mail->Password   = $_ENV['SMTP_PASSWORD'];
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;
                

                // Recipients
                $mail->setFrom($_ENV['SMTP_USERNAME'], 'Emoticart');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body    = "Click this link to reset your password: <a href='" . $reset_link . "'>" . $reset_link . "</a>";

                // Send the email
                $mail->send();
                $message = "A password reset link has been sent to your email.";
            } catch (Exception $e) {
                $error_message = "Failed to send the password reset email. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $error_message = "No account found with that email address.";
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
input[type="email"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
}

</style>


<section id="hero" class="hero section">
    <div class="container" id="container-login">
        <h2>Forgot Password</h2>
        <!-- Display message if it exists -->
        <?php if (!empty($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php elseif (!empty($message)): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <input type="email" name="email" placeholder="Enter your email" required>
            <div class="download-container">
                <a href="login.php" id="retrieve-cancel" class="btn-get-started">Cancel</a>
                <button class="btn-get-started" id="retrieve-username" type="submit">Retrieve</button>
            </div>
        </form>
    </div>
</section>

<?php
include('footer.php');
?>
