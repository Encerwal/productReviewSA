<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; 
// UNCOMMENT THIS FOR LOCAL PHP MAILER
#$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
#$dotenv->load();
$error_message = '';
$message = '';
$registration_success = isset($_GET['email_sent']) && $_GET['email_sent'] == 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(50));

    try {
        $host = $_ENV['DATABASE_HOST'];
        $db_password = $_ENV['DATABASE_PASSWORD'];
        #$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=emoticart;user=postgres;password=102475");
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
            // Temporarily insert user data with the confirmation token and unconfirmed status
            $sql = "INSERT INTO users (username, email, password, token, email_confirmed) 
                    VALUES (:username, :email, :password, :token, false)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password, 'token' => $token]);

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
                $confirmation_link = "https://emoticart.onrender.com/confirm_email.php?token=$token";
                $mail->Subject = 'Email Confirmation Required';
                $mail->Body    = "Please click on this link to confirm your email: <a href='$confirmation_link'>$confirmation_link</a>";

                // Send the email
                $mail->send();
                $message = "A confirmation email has been sent to your address.";
            } catch (Exception $e) {
                $error_message = "Error sending confirmation email: " . $mail->ErrorInfo;
            }

            // Redirect to a thank you page
            header("Location: register.php?email_sent=1");
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
    <div class="container">
        <?php if ($registration_success): ?>
                <div class="alert-wrapper">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Email sent!</strong> Check your Email for Account Confirmation.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
        <?php endif; ?>
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
    </div>
</section>

<?php
include('footer.php');
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
