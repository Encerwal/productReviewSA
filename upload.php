<?php
session_start();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'sample'){
        
        $sampleFile = 'uploads/TestReviewsWithDates.csv';
        // Manually simulate $_FILES handling
        $file_name = basename($sampleFile);
        $file_tmp = $sampleFile;
        $file_type  = 'text/csv';
        $file['size'] = filesize($sampleFile);
        $file['error'] = 0;

        // cURL to send the file to the Flask server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://wxo66g09eg7m.share.zrok.io/process_csv");
        #curl_setopt($ch, CURLOPT_URL, "http://localhost:5000/process_csv");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Attach file
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'file' => new CURLFile($file_tmp, $file_type, $file_name)
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);

        // Save the JSON response to session or temporary file
        $_SESSION['file_data'] = $response;

        // Redirect to result.php
        header("Location: result.php");
        exit();
    }else if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];

        // Define allowed file types and maximum file size (10MB)
        $allowed_types = array('csv' => 'text/csv');
        $max_size = 10 * 1024 * 1024; // 10MB

        // Get file extension
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

        // Verify file extension and size
        if (!array_key_exists($file_ext, $allowed_types) || $file_type !== $allowed_types[$file_ext]) {
            die("Error: Please select a valid CSV file.");
        }

        if ($file_size > $max_size) {
            die("Error: File size is larger than the allowed limit of 10MB.");
        }

        // cURL to send the file to the Flask server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://wxo66g09eg7m.share.zrok.io/process_csv"); // Flask server endpoint
        #curl_setopt($ch, CURLOPT_URL, "http://localhost:5000/process_csv");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Attach file
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'file' => new CURLFile($file_tmp, $file_type, $file_name)
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);

        // Save the JSON response to session or temporary file
        $_SESSION['file_data'] = $response;

        // Redirect to result.php
        header("Location: result.php");
        exit();
    } else {
        
        echo "Error: " . $_FILES['file']['error'];
    }
}
?>


<?php include('header.php'); ?>
<main>
    <section id="hero" class="hero section">
        <div class="container d-flex justify-content-center align-items-start" id="single-container">
            <div class="row gy-4">
                <div class="col-lg-12 d-flex flex-column justify-content-center text-center">
                    <h1>Sentiment Analysis Checker</h1>
                      <form id="file-upload-form" action="upload.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" id="form-action" value="">
                        <p>Upload a file for Sentiment Analysis or use a <button class="btn btn-link" type="submit" id="sample-link">sample file</button> </p>
                        
                        <!-- Drag and Drop File Upload Area -->
                        <div id="drop-area" class="drop-area">
                            <p>Drag and drop a CSV file here, or click this area to browse files</p>
                            <input type="file" name="file" id="file" accept=".csv" required hidden>
                        </div>
                        <button class="btn-get-started" type="submit" id="btn-analyze" name="action" value='upload'>Analyze File</button>
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
<?php include('footer.php'); ?>

<!-- JavaScript to handle Drag and Drop -->
<script>
  const dropArea = document.getElementById('drop-area');
  const fileInput = document.getElementById('file');

  // Prevent default drag behaviors
  ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
    document.body.addEventListener(eventName, preventDefaults, false);
  });

  // Highlight drop area when item is dragged over it
  ['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, () => dropArea.classList.add('highlight'), false);
  });

  ['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, () => dropArea.classList.remove('highlight'), false);
  });

  // Handle dropped files
  dropArea.addEventListener('drop', handleDrop, false);

  // Prevent default behavior for drag and drop events
  function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }

  // Handle file drop
  function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;

    fileInput.files = files;
    updateDropAreaText(files[0].name); 
  }

  // Add click event to open file dialog
  dropArea.addEventListener('click', () => {
    fileInput.click();
  });

  // Update drop area text on file select
  fileInput.addEventListener('change', () => {
    if (fileInput.files.length) {
      updateDropAreaText(fileInput.files[0].name);
    }
  });

  // Function to update drop area text
  function updateDropAreaText(filename) {
    dropArea.querySelector('p').textContent = `File Selected: ${filename}`;
  }

  // For sample button
  document.getElementById('sample-link').addEventListener('click', function() {
    document.getElementById('form-action').value = 'sample';
    document.getElementById('file-upload-form').submit();
  });

</script>

<style>
  .drop-area {
    border: 2px dashed #ddd;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    margin-top: 20px;
  }

  .drop-area.highlight {
    border-color: #06c;
  }
</style>
