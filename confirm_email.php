<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        $host = $_ENV['DATABASE_HOST'];
        $db_password = $_ENV['DATABASE_PASSWORD'];

        $pdo = new PDO("pgsql:host=$host;port=5432;dbname=emoticart;user=emoticart;password=$db_password");
        #$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=emoticart;user=postgres;password=102475");
        // Verify the token
        $sql = "SELECT * FROM users WHERE token = :token AND email_confirmed = false";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Confirm the user's email
            $sql = "UPDATE users SET email_confirmed = true, token = NULL WHERE token = :token";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['token' => $token]);

            // Redirect to the login page with a success message
            header("Location: login.php?registration_success=1");
            exit();
        } else {
            alert('Invalid or expired token.');
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
