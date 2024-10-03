<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader (if you installed via Composer)
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    try {
        $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=emoticart;user=postgres;password=102475");
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
            $reset_link = "http://localhost/ThesisWeb/login/reset_password.php?token=" . $token;

            // Send the email using PHPMailer
            $mail = new PHPMailer(true);
            
            try {
                // Server settings
                $mail->isSMTP();                                        // Set mailer to use SMTP
                $mail->Host       = 'smtp.gmail.com';                   // Specify SMTP server
                $mail->SMTPAuth   = true;                               // Enable SMTP authentication
                $mail->Username   = 'emoticart2024@gmail.com';          // Your Gmail email
                $mail->Password   = 'gibu mutc ufaj rzox';              // Your Gmail password or app password
                $mail->SMTPSecure = 'tls';                              // Enable TLS encryption
                $mail->Port       = 587;                                // TCP port for TLS

                // Recipients
                $mail->setFrom('emoticart2024@gmail.com', 'Emoticart');
                $mail->addAddress($email);                              // Add recipient

                // Content
                $mail->isHTML(true);                                    // Set email format to HTML
                $mail->Subject = 'Password Reset Request';
                $mail->Body    = "Click this link to reset your password: <a href='" . $reset_link . "'>" . $reset_link . "</a>";

                // Send the email
                $mail->send();
                echo "A password reset link has been sent to your email.";
            } catch (Exception $e) {
                echo "Failed to send the password reset email. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "No account found with that email address.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="post" action="">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>
