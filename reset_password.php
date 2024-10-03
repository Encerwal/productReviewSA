<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=emoticart;user=postgres;password=102475");

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

            // Show a success message and redirect the user back to the login page
            echo "<div style='text-align: center; padding: 20px;'>";
            echo "<h2>Password reset successful!</h2>";
            echo "<p>You will be redirected to the login page in 5 seconds.</p>";
            echo "</div>";

            // Redirect to the login page after 5 seconds
            header("refresh:5;url=login.php");
            exit(); // Ensure no further code is executed
        } else {
            echo "Invalid or expired token.";
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
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form method="post">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        <input type="password" name="password" placeholder="Enter your new password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
