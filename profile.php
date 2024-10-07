<?php
// Include the authentication helper
require_once 'auth.php';

// Ensure the user is logged in
requireLogin();

// Connect to the database (modify the connection details accordingly)
$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=emoticart;user=postgres;password=102475");

// Initialize feedback message
$feedback_message = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle username update
    if (isset($_POST['new_username'])) {
        $new_username = $_POST['new_username'];

        // Validate new username
        if (!empty($new_username)) {
            try {
                // Update the username in the database
                $sql = "UPDATE users SET username = :new_username WHERE id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['new_username' => $new_username, 'user_id' => $_SESSION['user_id']]);

                // Update the session username
                $_SESSION['username'] = $new_username;
                $feedback_message = "Username updated successfully!";
            } catch (PDOException $e) {
                // Check if the error is due to a unique constraint violation (duplicate username)
                if ($e->getCode() == '23505') {
                    $feedback_message = "The username is already taken. Please choose another one.";
                } else {
                    $feedback_message = "An error occurred: " . htmlspecialchars($e->getMessage());
                }
            }
        } else {
            $feedback_message = "Please enter a valid username.";
        }
    }

    // Handle password update
    if (isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Fetch the current user details
        $sql = "SELECT password FROM users WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify current password
        if (password_verify($current_password, $user['password'])) {
            // Check if new password and confirm password match
            if ($new_password === $confirm_password) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $sql = "UPDATE users SET password = :new_password WHERE id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['new_password' => $hashed_password, 'user_id' => $_SESSION['user_id']]);

                $feedback_message = "Password updated successfully!";
            } else {
                $feedback_message = "New password and confirmation do not match.";
            }
        } else {
            $feedback_message = "Current password is incorrect.";
        }
    }
}
?>

<?php include('header.php'); ?>

<style>
    .profile-container {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    form {
        margin-bottom: 20px;
    }
    input[type="text"], input[type="password"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .feedback {
        color: green;
        text-align: center;
    }
</style>
<section id="hero" class="hero section">
    <div class="profile-container">
        <h2>Your Profile</h2>

        <!-- Display feedback message -->
        <?php if (!empty($feedback_message)): ?>
            <div class="feedback"><?php echo $feedback_message; ?></div>
        <?php endif; ?>

        <!-- Form to update username -->
        <form method="post">
            <h3>Change Username</h3>
            <input type="text" name="new_username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" placeholder="New Username" required>
            <button class="btn-get-started" id="btn-analyze" type="submit">Update Username</button>
        </form>

        <!-- Form to update password -->
        <form method="post">
            <h3>Change Password</h3>
            <input type="password" name="current_password" placeholder="Current Password" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            <button class="btn-get-started" id="btn-analyze" type="submit">Update Password</button>
        </form>
    </div>
</section>
<?php include('footer.php'); ?>
