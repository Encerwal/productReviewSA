<?php
session_start(); // Start the session

include('header.php');

// Check if file data is available in the session
if (isset($_SESSION['file_data'])) {
    // Decode the JSON data
    $data = json_decode($_SESSION['file_data'], true);
    $data = json_decode($data, true);

    // Check if decoding was successful
    if (json_last_error() === JSON_ERROR_NONE) {
        if (is_array($data)) {
            echo "<h2>Analysis Results</h2>";
            echo "<table border='1'>";
            echo "<tr><th>Date</th><th>Text</th><th>Predicted Sentiment</th></tr>";

            foreach ($data as $row) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['text']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Predicted_Sentiment']) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
         
            echo "Error: Data is not an array.";
        }
    } else {
        echo "Error: Invalid JSON data. " . json_last_error_msg();
    }

    // Clear session data
    unset($_SESSION['file_data']);
} else {
    echo "Error: No file data found.";
}

include('footer.php');
?>