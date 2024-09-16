<?php
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if a file was uploaded without errors
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
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

        // Define the upload directory
        $upload_dir = "uploads/";

        // Create the directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Move the uploaded file to the target directory
        $file_path = $upload_dir . basename($file_name);
        if (move_uploaded_file($file_tmp, $file_path)) {
            // Redirect to analysis.php with the file name as a query parameter
            header("Location: analysis.php?file=" . urlencode($file_name));
            exit();
        } else {
            echo "Error: There was a problem uploading your file. Please try again.";
        }
    } else {
        echo "Error: " . $_FILES['file']['error'];
    }
}
?>

<?php
// Include the header
include('header.php');
?>

<main>
<section id="hero" class="hero section">
<div class="container d-flex justify-content-center align-items-start" id="single-container">
  <div class="row gy-4">
    <div class="col-lg-12 d-flex flex-column justify-content-center text-center">
        <h1>Sentiment Analysis Checker</h1>
        <p>Use Sentiment Analysis to automatically classify opinions as positive and negative</p>

        <form id="file-upload-form" action="upload.php" method="post" enctype="multipart/form-data">
            <!-- Drag and Drop File Upload Area -->
            <div id="drop-area" class="drop-area">
                    <p>Drag and drop a CSV file here, or click this area to browse files</p>
                    <input type="file" name="file" id="file" accept=".csv" required hidden>
            </div>
            <button class="btn-get-started" type="submit" id="btn-analyze">Analyze File</button>
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

</section><!-- /Hero Section -->
    
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

    fileInput.files = files; // Update file input
    updateDropAreaText(files[0].name); // Update drop area text with the file name
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
