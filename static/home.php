<?php
// for checking errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the header
include('header.php');
?>

<!-- Main Content of the Home Page -->
<main>
    <section>
        <div class="container">
            <h1>Welcome to Our Website!</h1>
            <p>This is the homepage of your Thesis Project. Here you can find information about our mission, team, and how to get involved.</p>

            <!-- Buttons Section -->
            <div class="button-container">
                <a href="single_input.php" class="button">Single Input</a>
                <a href="upload.php" class="button">CSV File</a>
            </div>
        </div>
    </section>
</main>

<?php
// Include the footer
include('footer.php');
?>