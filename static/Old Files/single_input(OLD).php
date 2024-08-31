<?php
// Include the header
include('header.php');

// Initialize variables
$input_text = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_text = $_POST['user_input'];
    // Redirect to result.php with the input text as a query parameter
    header("Location: single_result.php?user_input=" . urlencode($input_text));
    exit();
}
?>

<!-- Main Content of the Single Input Page -->
<main>
    <section>
        <div class="container">
            <h1>Sentiment Analysis Single Input</h1>
            <p>Enter your text below for sentiment analysis:</p>
            <form id="sentiment-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-wrapper">
                    <div class="input-wrapper">
                        <input type="text" name="user_input" placeholder="Enter your input here" required>
                        <button type="submit">Submit</button>
                    </div>

                    <!-- Loader -->
                    <div id="loader-container" style="display: none;">
                        <img src="images/loading.gif" alt="Loading..." style="width: 100px; height: auto;">
                    </div>
                </div>
            </form>
        </div>
    </section>
</main>

<?php
// Include the footer
include('footer.php');
?>


<script>
    // JavaScript to show loader and hide previous results when form is submitted
    document.getElementById('sentiment-form').addEventListener('submit', function() {
        // Show loader
        document.getElementById('loader-container').style.display = 'flex';

        // Hide previous results
        document.getElementById('result-container').style.display = 'none';
    });
</script>