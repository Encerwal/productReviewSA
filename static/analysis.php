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
<style>
    .grid-container {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        gap: 1px;
        height: 100vh;
    }

    .grid-item {
        border: 1px solid black; /* For visual reference */
        padding: 20px;
        box-sizing: border-box;
    }

    .item1 {
        grid-column: 1 / 2;
        grid-row: 1 / 3;
    }

    .item2 {
        grid-column: 2 / 3;
        grid-row: 1 / 2;
    }

    .item3 {
        grid-column: 2 / 3;
        grid-row: 2 / 3;
    }

    .item4 {
        grid-column: 3 / 4;
        grid-row: 1 / 2;
    }

    .item5 {
        grid-column: 3 / 4;
        grid-row: 2 / 3;
    }
       /* Base Styles */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        line-height: 1.6;
    }

    header {
        background-color: #ADD8E6; /* Light blue */
        color: white;
        padding: 1rem 2rem;
        text-align: center;
    }

    main {
        padding: 2rem;
        max-width: 1600px;
        margin: 0 auto;
    }

    h2 {
        text-align: center;
        margin-bottom: 2rem;
    }

    div {
        overflow-x: auto; /* Horizontal scroll on small screens */
        margin-bottom: 2rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    a {
        display: inline-block;
        padding: 10px 20px;
        background-color: #ADD8E6; /* Light blue */
        color: white;
        text-decoration: none;
        border-radius: 5px;
        text-align: center;
    }

    a:hover {
        background-color: #5F9EA0; /* Darker blue */
    }

    footer {
        background-color: #ADD8E6; /* Light blue */
        color: white;
        text-align: center;
        padding: 1rem 0;
        position: relative;
        bottom: 0;
        width: 100%;
    }

    /* Grid Layout */
    .grid-container {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        gap: 1px;
        height: 100vh;
    }

    .grid-item {
        border: 1px solid black; /* For visual reference */
        padding: 20px;
        box-sizing: border-box;
    }

    .item1 {
        grid-column: 1 / 2;
        grid-row: 1 / 3;
    }

    .item2 {
        grid-column: 2 / 3;
        grid-row: 1 / 2;
    }

    .item3 {
        grid-column: 2 / 3;
        grid-row: 2 / 3;
    }

    .item4 {
        grid-column: 3 / 4;
        grid-row: 1 / 2;
    }

    .item5 {
        grid-column: 3 / 4;
        grid-row: 2 / 3;
    }

    
    /* Media Queries */
    @media (max-width: 1200px) {
        .grid-container {
            grid-template-columns: 1fr;
            grid-template-rows: repeat(5, auto);
            gap: 10px;
            height: auto;
        }

        .item1, .item2, .item3, .item4, .item5 {
            grid-column: 1 / 2;
            grid-row: auto;
        }

        header, footer {
            padding: 1rem;
        }

        main {
            padding: 1rem;
        }

        h2 {
            font-size: 1.5rem;
        }

        a {
            display: block;
            width: 100%;
            margin: 1rem 0;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        header, footer {
            font-size: 1rem;
        }

        h2 {
            font-size: 1.2rem;
        }

        table, th, td {
            font-size: 0.9rem;
        }
    }
</style>
    <main>
        <h2>Results</h2>
        <div class="grid-container">
            <div class="grid-item item1">
                <!-- Ensure that HTML output from Python is not escaped -->
                <?php echo $output; ?>
            </div>
            <div class="grid-item item2">Content 2</div>
            <div class="grid-item item3">Content 3</div>
            <div class="grid-item item4">Content 4</div>
            <div class="grid-item item5">Content 5</div>
        </div>
        <a href="upload.php">Upload Another File</a>
    </main>

    <?php include('footer.php'); ?>
</body>
</html>
