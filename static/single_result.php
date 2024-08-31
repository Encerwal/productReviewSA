<?php
// Include the header
include('header.php');

// Function to call the Flask API for sentiment analysis
function analyze_sentiment($input_text) {
    $url = 'http://localhost:5000/analyze?text=' . urlencode($input_text);
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    return $data['sentiment'];
}

// Initialize variables
$input_text = "";
$sentiment_result = "";

// Check if the user input is passed via the query parameter
if (isset($_GET['user_input'])) {
    $input_text = $_GET['user_input'];
    $sentiment_result = analyze_sentiment($input_text);
}
?>

<!-- Main Content of the Result Page -->
<main>
    <section>
        <div class="container">
            <h1>Sentiment Analysis Result</h1>

            <?php if (!empty($sentiment_result)): ?>
                <h2>Analysis Result:</h2>
                <p><strong>Input:</strong> <?php echo htmlspecialchars($input_text); ?></p>
                <p><strong>Predicted Sentiment Class:</strong> <?php
                    if ($sentiment_result == 1) {
                        echo "Positive";
                    } elseif ($sentiment_result == 0) {
                        echo "Negative";
                    }
                    ?>
                </p>               
                <!-- Display image based on sentiment result -->
                <?php if ($sentiment_result == 1): ?>
                    <img src="images/happy.png" alt="Happy" style="width: 100px; height: auto;">
                <?php elseif ($sentiment_result == 0): ?>
                    <img src="images/sad.png" alt="Sad" style="width: 100px; height: auto;">
                <?php endif; ?>
                <p class="note">Note: Consider reading more feedbacks before buying a product!</p>
            <?php else: ?>
                <p>No analysis result available.</p>
            <?php endif; ?>

            <a href="single_input.php">Analyze Another Text</a>
        </div>
    </section>
</main>

<?php
// Include the footer
include('footer.php');
?>
