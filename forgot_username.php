<?php
include('header.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader (if PHPMailer is installed via Composer)
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
        // Check if the email exists in the database
        $sql = "SELECT username FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $usernames = $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch all usernames associated with the email

        if ($usernames) {
            // Prepare the list of usernames
            $username_list = implode(", ", $usernames);

            // Send the username(s) via email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();                                        // Set mailer to use SMTP
                $mail->Host       = 'smtp.gmail.com';                   // Specify SMTP server
                $mail->SMTPAuth   = true;                               // Enable SMTP authentication
                $mail->Username   = $_ENV['SMTP_USERNAME'];             // Your Gmail email
                $mail->Password   = $_ENV['SMTP_PASSWORD'];             // Your Gmail password or app password
                $mail->SMTPSecure = 'tls';                              // Enable TLS encryption
                $mail->Port       = 587;                                // TCP port for TLS

                // Recipients
                $mail->setFrom($_ENV['SMTP_USERNAME'], 'Emoticart');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Username Recovery';
                $mail->Body = "Here is your username associated with this email: " . $username_list;

                // Send the email
                $mail->send();
                $message= "An email with your username has been sent to your email address.";
            } catch (Exception $e) {
                $error_message = "Failed to send the username reminder email. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $error_message =  "No account found with that email address.";
        }
    } catch (PDOException $e) {
        $error_message =  "Error: " . $e->getMessage();
    }
}
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
input[type="email"] {
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
        <h2>Forgot Username</h2>
        <!-- Display message if it exists -->
        <?php if (!empty($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php elseif (!empty($message)): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button class="btn-get-started" id="btn-analyze" type="submit">Retrieve Username</button>
        </form>
    </div>      
</section>
<?php
include('footer.php');
?>
