<?php
// Include the header
include('header.php');

// Function to run the Python script and get the sentiment analysis result
function analyze_sentiment($input_text) {
    $command = escapeshellcmd("py single_analysis.py " . escapeshellarg($input_text));
    $result = shell_exec($command);
    return $result;
}

// Initialize variables
$input_text = "";
$sentiment_result = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_text = $_POST['user_input'];
    $sentiment_result = analyze_sentiment($input_text);
}
?>

<style>
    body {
    margin: 0;
    font-family: Arial, sans-serif;
}

.container {
    display: flex;
    flex-direction: column;
    align-items: center; /* Center horizontally */
    padding: 20px;
    box-sizing: border-box;
}

.input-wrapper {
    max-width: 100%;
    width: 400px; /* Adjust as needed */
    margin: 20px 0;
}

input[type="text"] {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

h1 {
    margin-bottom: 20px;
}

/* Responsive styling for mobile devices */
@media (max-width: 600px) {
    .input-wrapper {
        width: 100%;
    }

    input[type="text"] {
        font-size: 14px;
        padding: 8px;
    }
}
</style>

<!-- Main Content of the Single Input Page -->
<main>
    <section>
        <div class="container">
            <h1>Sentiment Analysis</h1>
            <p>Enter your text below for sentiment analysis:</p>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="input-wrapper">
                    <input type="text" name="user_input" placeholder="Enter your input here" required value="<?php echo htmlspecialchars($input_text); ?>">
                    <button type="submit">Submit</button>
                </div>
            </form>

            <?php if (!empty($sentiment_result)): ?>
                <div class="result">
                    <h2>Analysis Result:</h2>
                    <p><strong>Input:</strong> <?php echo htmlspecialchars($input_text); ?></p>
                    <p><strong>Predicted Sentiment Class:</strong> <?php echo htmlspecialchars($sentiment_result); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
// Include the footer
include('footer.php');
?>
