<?php
session_start();
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "User is not logged in."]);
    exit();
}

// Handle the POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $product_data = $_POST['product_data'];
    $user_id = $_SESSION['user_id'];

    if (empty($product_name) || empty($product_data)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid input."]);
        exit();
    }

    try {
        //$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=emoticart;user=postgres;password=102475");
        $host = $_ENV['DATABASE_HOST'];
        $password = $_ENV['DATABASE_PASSWORD'];

        $pdo = new PDO("pgsql:host=$host;port=5432;dbname=emoticart;user=emoticart;password=$password");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $countQuery = "SELECT COUNT(*) FROM saved_products WHERE user_id = :user_id";
        $stmt = $pdo->prepare($countQuery);
        $stmt->execute(['user_id' => $user_id]);
        $savedProductsCount = $stmt->fetchColumn();

        if ($savedProductsCount >= 3) {
            http_response_code(403);
            echo json_encode(["status" => "error", "message" => "You have already saved the maximum of 3 products."]);
            exit();
        }

        $sql = "INSERT INTO saved_products (user_id, product_name, product_details) VALUES (:user_id, :product_name, :product_details)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'user_id' => $user_id,
            'product_name' => $product_name,
            'product_details' => $product_data
        ]);

        echo json_encode(["status" => "success", "message" => "Product saved successfully."]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Error saving product: " . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
