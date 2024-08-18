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

<!-- Include Oswald font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap">

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
        font-size: 16px;
        border: 3px solid #2C3E50;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    button {
        margin-top: 10px; /* Space between the input field and button */
        padding: 10px 20px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: white;
        cursor: pointer;
        display: inline-block; /* Ensure it's not taking full width */
    }

    button:hover {
        background-color: #0056b3;
    }

    h1 {
        margin-bottom: 20px;
        font-size: 36px; /* Adjust size as needed */
        font-family: 'Oswald', sans-serif; /* Use Oswald font */
        font-weight: bold; /* Make text bold */
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

<!-- Main Content of the Single Input Page -->
<main>
    <section>
        <div class="container">
            <h1>Sentiment Analysis Single Input</h1>
            <p>Enter your text below for sentiment analysis:</p>
            <form id="sentiment-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-wrapper">
                    <div class="input-wrapper">
                        <input type="text" name="user_input" placeholder="Enter your input here" required value="<?php echo htmlspecialchars($input_text); ?>">
                        <button type="submit">Submit</button>
                    </div>

                    <!-- Results container -->
                    <div id="result-container" class="result">
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
                            <p class = "note">Note: Consider reading more feedbacks before buying a product!</p>
                        <?php endif; ?>
                        
                    </div>

                    <!-- Loader -->
                    <div id="loader-container">
                        <img src="images/loading.gif" alt="Loading...">
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