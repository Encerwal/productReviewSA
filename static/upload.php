<?php
session_start(); // Start the session

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

        // cURL to send the file to the Flask server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost:5000/process_csv"); // Flask server endpoint
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

                <!-- Your other HTML content -->
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
