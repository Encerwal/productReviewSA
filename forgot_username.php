<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader (if PHPMailer is installed via Composer)
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    try {
        $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=emoticart;user=postgres;password=102475");
        
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
                $mail->Username   = 'emoticart2024@gmail.com';          // Your Gmail email
                $mail->Password   = 'gibu mutc ufaj rzox';              // Your Gmail password or app password
                $mail->SMTPSecure = 'tls';                              // Enable TLS encryption
                $mail->Port       = 587;                                // TCP port for TLS


                // Recipients
                $mail->setFrom('emoticart2024@gmail.com', 'Emoticart');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Your Username(s) Request';
                $mail->Body = "Here is your username associated with this email: " . $username_list;

                // Send the email
                $mail->send();
                echo "<p style='text-align:center;'>An email with your username(s) has been sent to your email address.</p>";
            } catch (Exception $e) {
                echo "<p style='text-align:center;'>Failed to send the username reminder email. Mailer Error: {$mail->ErrorInfo}</p>";
            }
        } else {
            echo "<p style='text-align:center;'>No account found with that email address.</p>";
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
    <title>Forgot Username</title>
</head>
<body>
    <h2>Forgot Username</h2>
    <form method="post">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Retrieve Username</button>
    </form>
</body>
</html>
