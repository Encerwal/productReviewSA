<?php
require_once 'auth.php';
requireLogin();
include('header.php');
$user_id = $_SESSION['user_id'];

// Connect to the database
try {
    //$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=emoticart;user=postgres;password=102475");
    $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=emoticart;user=emoticart;password=102475");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to retrieve the products saved by the user
    $sql = "SELECT item_id, product_name, TO_CHAR(date_saved, 'YYYY-MM-DD HH24:MI') AS date_saved 
        FROM saved_products WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);

    // Fetch all the products for display
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $product_count = count($products);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<style>
    
    /* Overlay styling */
    .overlay-results {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 1000;
    }

</style>

<main class="main">
    <section id="hero" class="hero section">
        <div class="product-list">
            <div class="header-container">
                <div>
                    <h1>Manage Your Products</h1>
                    <?php if (!empty($products)): ?>
                        <p>Click on the products to see the saved results.</p>
                    <?php endif; ?>
                </div>
                <?php if (!empty($products)): ?>
                <button class="btn-get-started" onclick="toggleEditMode()">Edit</button>
                <?php endif; ?>
            </div>
            <ul>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div>
                            <li class="product-item">
                                <a href="show_product.php?id=<?php echo $product['item_id']; ?>">
                                    <strong><?php echo htmlspecialchars($product['product_name']); ?></strong>
                                    Saved on: <?php echo htmlspecialchars($product['date_saved']); ?>
                                </a>
                                <button type="button" class="delete-button" onclick="openDeleteModal('<?php echo $product['item_id']; ?>')">
                                <i class="bi bi-trash"></i>
                                </button>
                            </li>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>You haven't saved any products yet.</p>
                <?php endif; ?>

                <!-- Display the upload link if the user has less than 3 products -->
                <?php if ($product_count < 3): ?>
                    <strong>
                        <li class="product-item">
                            <a href="upload.php">+ Add New Product</a>
                        </li>
                    </strong>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Modal for delete confirmation -->
        <div id="overlay-results" class="overlay-results"></div>
        <div id="deleteModal" class="deleteModal">
            <h2>Delete Product</h2>
            <p>Are you sure you want to delete this product?</p>
            <form id="deleteForm" action="delete_product.php" method="POST">
                <input type="hidden" name="item_id" id="deleteItemId">
                <div class="download-container">
                    <button type="submit" class="btn-get-started" id="delete">Delete</button>
                    <button type="button" class="btn-get-started" id="cancel-delete" onclick="closeDeleteModal()">Cancel</button>
                </div>
            </form>
        </div>
    </section>

    
</main>

<script>
    const overlay = document.getElementById("overlay-results");
    function toggleEditMode() {
        // Get all delete buttons
        const deleteButtons = document.querySelectorAll('.delete-button');
        deleteButtons.forEach(button => {
            // Toggle the 'show' class to show/hide delete buttons
            button.classList.toggle('show');
        });
    }

    // Open delete confirmation modal
    function openDeleteModal(itemId) {
        overlay.style.display = "block"; // Show the darkened background
        document.getElementById('deleteItemId').value = itemId;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    // Close delete confirmation modal
    function closeDeleteModal() {
        overlay.style.display = "none";
        document.getElementById('deleteModal').style.display = "none"
    }

      // Close the modal when clicking outside the modal content
    overlay.onclick = function() {
        overlay.style.display = "none";
        document.getElementById('deleteModal').style.display = "none"
    }
</script>

<?php
include('footer.php');
?>
