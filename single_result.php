<?php
include('header.php');

// Function to call the Flask API for sentiment analysis
function analyze_sentiment($input_text) {
    $url = 'https://wxo66g09eg7m.share.zrok.io/analyze?text=' . urlencode($input_text);
    #$url = 'http://localhost:5000/analyze?text=' . urlencode($input_text);
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
<style>
    .sentiment-box {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 50px;
        background-color: #FFFFFF;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
        width: 100%;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        
        display: flex;
        align-items: center;
        gap: 40px;
        justify-content: center;
    }

    .sentiment-text {
        
        text-align: left;
        
    }

    .sentiment-title {
        margin-bottom: 5px;
    }

    .sentiment-text p {
        margin: 0;
        margin-bottom: 5px;
    }

    .sentiment-image {
        flex-shrink: 0;
        width: 100px;
        height: auto;
        
    }
    .again a{
        margin-top: 30px;
    }

    .single-result-container h1{
        margin-bottom: 10px;
    }
</style>

<!-- Main Content of the Single Input Page -->
<main class="main">

<section id="hero" class="hero section">

<div class="container d-flex justify-content-center align-items-start" id="single-container-Result">
    <div class="row gy-4">
        <div class="single-result-container col-lg-12 d-flex flex-column justify-content-center text-center">
            <h1>Analysis Completed</h1>
            <p>The result of your analysis is displayed below. If you would like to analyze another text, please click the button below</p>

            <!-- Box container for sentiment result -->
            <div class="sentiment-box">
                    <div>
                        <!-- Display image based on sentiment result -->
                        <?php if (isset($sentiment_result)): ?>
                            <?php if ($sentiment_result == 1): ?>
                                <img class="sentiment-image" src="assets/img/like.png" alt="Happy">
                            <?php elseif ($sentiment_result == 0): ?>
                                <img class="sentiment-image" src="assets/img/dislike.png" alt="Sad">
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="sentiment-text">
                        <?php if (isset($sentiment_result)): ?>
                            <?php if ($sentiment_result == 1): ?>
                                <p class="sentiment-title"><strong>Positive Sentiment</strong></p>
                                <p>The text was classified as Positive.</p>
                            <?php elseif ($sentiment_result == 0): ?>
                                <p class="sentiment-title"><strong>Negative Sentiment</strong></p>
                                <p>The text was classified as Negative.</p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p>No analysis result available.</p>
                        <?php endif; ?>
                    </div>
                
            </div> 
        </div>
        <div class="again d-flex flex-column flex-md-row justify-content-center">
            <a href="single_input.php" class="btn-get-started">Analyze Another Text<i class="bi bi-arrow-right"></i></a>
        </div>
      
    </div>
</div>

</section>
</main>

<?php
// Include the footer
include('footer.php');
?>