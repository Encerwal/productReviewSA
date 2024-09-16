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

// Check if the user input is passed via the query parameter
if (isset($_GET['user_input'])) {
    $input_text = $_GET['user_input'];
    $sentiment_result = analyze_sentiment($input_text);
}
?>

<!-- Include Oswald font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap">

<style>
    body {
        margin: 0;
       
    }

    .container {
        display: flex;
        flex-direction: column;
        align-items: center; /* Center horizontally */
        padding: 20px;
        box-sizing: border-box;
    }

    .form-wrapper {
        position: relative; /* Make this container relative for absolute positioning */
        max-width: 100%;
        width: 600px; /* Adjust as needed */
    }

    .input-wrapper {
        width: 100%;
        text-align: center;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 3px solid #2C3E50;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

  
    /* Loader container styling */
    #loader-container {
        position: absolute;
        top: 100%; /* Position it below the form */
        left: 50%;
        transform: translateX(-50%);
        margin-top: 20px; /* Space between submit button and loader */
        display: none; /* Hidden by default */
        width: 100%;
        text-align: center;
    }

    #loader-container img {
        width: 100%; /* Set width to a reasonable size */
        height: 100%; /* Set height to match width */
        object-fit: contain; /* Maintain aspect ratio */
    }

    .result {
        position: absolute;
        left: 50%;
        top: 150px;
        transform: translateX(-50%); /* Center horizontally */
        text-align: center; 
    }

    .note{
        color: gray;
        font-style: italic;
    }


    /* Responsive styling for mobile devices */
    @media (max-width: 768px) {
        .form-wrapper {
            width: 100%;
        }

        input[type="text"] {
            font-size: 14px;
            padding: 8px;
        }
    }
</style>

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
