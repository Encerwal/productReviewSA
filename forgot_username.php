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
        $sql = "SELECT username FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $usernames = $stmt->fetchAll(PDO::FETCH_COLUMN); 
        if ($usernames) {
            // Prepare the list of usernames
            $username_list = implode(", ", $usernames);

            // Send the username via email using PHPMailer
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
        <?php if (!empty($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php elseif (!empty($message)): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="post">
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
