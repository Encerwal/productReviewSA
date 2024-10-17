
<?php
session_start();
require_once 'auth.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
// UNCOMMENT THIS FOR LOCAL PHP MAILER
#$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
#$dotenv->load();

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    try {
        // Get environment variables for the database connection
        $host = $_ENV['DATABASE_HOST'];
        $db_password = $_ENV['DATABASE_PASSWORD'];
        // Connect to the database
        $pdo = new PDO("pgsql:host=$host;port=5432;dbname=emoticart;user=emoticart;password=$db_password");
        #$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=emoticart;user=postgres;password=102475");

        // Check if the email is already confirmed
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['email_confirmed'] == 't') {
                $success_message = "This email is already confirmed. You can log in.";
            } else {
                // Generate a new confirmation token
                $new_token = bin2hex(random_bytes(16));

                // Update the token in the database
                $update_sql = "UPDATE users SET token = :token WHERE email = :email";
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->execute(['token' => $new_token, 'email' => $email]);

                // Create the confirmation link
                $confirmation_link = "https://emoticart.onrender.com/confirm_email.php?token=" . $new_token;

                // Send confirmation email using PHPMailer
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
                    $mail->Subject = 'Email Confirmation Request';
                    $mail->Body    = "Click this link to confirm your email: <a href='" . $confirmation_link . "'>" . $confirmation_link . "</a>";

                    // Send the email
                    $mail->send();
                    $success_message = "A new confirmation email has been sent. Please check your inbox.";
                } catch (Exception $e) {
                    $error_message = "Failed to send the confirmation email. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        } else {
            $error_message = "No account found with that email.";
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
        <h2>Resend Email Confirmation</h2>

        <?php if (!empty($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php elseif (!empty($success_message)): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="email" name="email" placeholder="Enter your email" required>
            <div class="download-container">
                <a href="login.php" id="retrieve-cancel" class="btn-get-started">Cancel</a>
                <button class="btn-get-started" type="submit">Send Email</button>
            </div>
        </form>
    </div>
</section>

<?php
include('footer.php');
?>
