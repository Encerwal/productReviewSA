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
<main class="main">

<section id="hero" class="hero section">

  <div class="container d-flex justify-content-center align-items-start" id="single-container">
    <div class="row gy-4">
      <div class="col-lg-12 d-flex flex-column justify-content-center text-center">
        <h1>Sentiment Analysis Checker</h1>
        <p>Use Sentiment Analysis to automatically classify opinions as positive and negative</p>
        <form id="sentiment-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <textarea class="form-control" name="user_input" rows="6" placeholder="Type your own customer feedback, reviews, or basic text" required=""></textarea>
          <div class="d-flex flex-column flex-md-row justify-content-center">
            <button class="btn-get-started" type="submit" id="btn-analyze"> Analyze Text</button>
          </div>
        </form>
      </div>

      <div class="single-help-title col-lg-12 d-flex flex-column justify-content-center text-center">
        <h2>How Sentiment Analysis can help you</h2>
      </div>
      <div class="single-help-text col-lg-4 col-md-4 d-flex align-items-start">
        <i class="bi bi-check2-square me-2"></i>
        <p>Quickly identify negative feedback and respond immediately</p>
      </div>
      <div class="single-help-text col-lg-4 col-md-4 d-flex align-items-start">
        <i class="bi bi-check2-square me-2"></i>
        <p>Discover the topics that make your customers most satisfied or dissatisfied.</p>
      </div>
      <div class="single-help-text col-lg-4 col-md-4 d-flex align-items-start">
        <i class="bi bi-check2-square me-2"></i>
        <p>Detect Sentiments for hundreds of reviews instantly</p>
      </div>
      
    </div>
  </div>
 
</section>
</main>

<?php
// Include the footer
include('footer.php');
?>


<script>
    $("a#btn-analyze").click(function()
    {
    $("#sentiment-form").submit();
    return false;
    });

    // JavaScript to show loader and hide previous results when form is submitted
    document.getElementById('sentiment-form').addEventListener('submit', function() {
        // Show loader
        document.getElementById('loader-container').style.display = 'flex';

        // Hide previous results
        document.getElementById('result-container').style.display = 'none';
    });
</script>