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
<style>
/* Basic reset */
body, h1, h2, h3, p, form, input, button {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
}

main {
    padding: 1rem;
    max-width: 1200px;
    margin: 0 auto;
}

form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

label, input, button {
    margin: 0.5rem 0;
}

input[type="file"] {
    display: block;
    margin: 1rem 0;
}

button {
    background-color: #4CAF50; /* Green button */
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}


@media (max-width: 600px) {
    header, footer {    
        padding: 0.5rem;
    }

    button {
        width: 100%;
        padding: 1rem;
        font-size: 1rem;
    }
}

</style>
<main>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="file">Choose a CSV file to upload:</label>
        <input type="file" name="file" id="file" accept=".csv" required>
        <button type="submit">Upload</button>
    </form>
</main>

<?php include('footer.php'); ?>
