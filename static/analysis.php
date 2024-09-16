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
        border: 1px solid black;
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


    /* Grid Layout */
    .grid-container {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        gap: 1px;
        height: 100vh;
    }

    .grid-item {
        border: 1px solid black;
        padding: 20px;
        box-sizing: border-box;
        overflow-x: auto;
        margin-bottom: 2rem;
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

<!-- Main Content of the Single Input Page -->
<main class="main">

<section id="hero" class="hero section">

  <div class="container d-flex justify-content-center align-items-start" id="single-container">
    <div class="row gy-4">
        <div class="col-lg-12 d-flex flex-column justify-content-center text-center">
            <h1>Results</h1>

            <div class="grid-container">
                <div class="grid-item item1">
                    <?php echo $output; ?>
                </div>
                <div class="grid-item item2">Content 2</div>
                <div class="grid-item item3">Content 3</div>
                <div class="grid-item item4">Content 4</div>
                <div class="grid-item item5">Content 5</div>
            </div>

            <a href="upload.php">Upload Another File</a>
        </div>
    </div>
  </div>
 
</section>
</main>

    <main>
        
       
    </main>

    <?php include('footer.php'); ?>
</body>
</html>
