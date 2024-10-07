<?php
require_once 'auth.php';
requireLogin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $user_id = $_SESSION['user_id'];

    // Connect to the database
    try {
        $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=emoticart;user=postgres;password=102475");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Delete the product where user_id and item_id match
        $sql = "DELETE FROM saved_products WHERE item_id = :item_id AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['item_id' => $item_id, 'user_id' => $user_id]);

        // Redirect to manage_products.php after deletion
        header("Location: manage_products.php");
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: manage_products.php");
    exit();
}
?>
