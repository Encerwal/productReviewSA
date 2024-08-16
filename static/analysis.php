<?php
// Get the file name from the query parameter
if (isset($_GET['file'])) {
    $file_name = $_GET['file'];
    $file_path = 'uploads/' . basename($file_name);

    // Convert backslashes to forward slashes
    //$file_path = str_replace('\\', '/', $file_path);

    // Debugging: Output the file path after conversion
    //echo "<pre>File Path: $file_path</pre>";

    // Check if the file exists
    if (file_exists($file_path)) {
        // Construct the command
        $command = escapeshellcmd("py process_csv.py " . escapeshellarg($file_path));

        // Debugging: Output the command for verification
        //echo "<pre>Command: $command</pre>";

        // Execute the command and capture the output and error
        $output = [];
        $return_var = 0;
        exec($command . ' 2>&1', $output, $return_var); // Capture both stdout and stderr

        // Convert output array to string
        $output = implode("\n", $output);

        // Display the output and error
        //echo "<pre>Output: $output</pre>";
        //echo "<pre>Return Code: $return_var</pre>";

        // Check if there was an error in the output
        if ($return_var === 0) {
            // Display the HTML content properly
            // echo $output;
        } else {
            $output = "Error: Failed to execute Python script. Return code: $return_var";
            echo $output;
        }
    } else {
        $output = "Error: The file does not exist.";
        echo $output;
    }
} else {
    $output = "Error: No file specified.";
    echo $output;
}
?>

<?php
// Include the header
include('header.php');
?>
    <main>
        <h2>First 50 Rows of the CSV File</h2>
        <div>
            <!-- Ensure that HTML output from Python is not escaped -->
            <?php echo $output; ?>
        </div>
        <a href="upload.php">Upload Another File</a>
    </main>

    <?php include('footer.php'); ?>
</body>
</html>
