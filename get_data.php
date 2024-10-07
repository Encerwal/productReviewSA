<?php
session_start();
require_once 'auth.php';

$rows_per_page = 15;

// Get the current page, sentiment, and category from the AJAX request
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$sentiment_filter = isset($_GET['sentiment']) ? $_GET['sentiment'] : 'all';
$category_filter = isset($_GET['category']) ? $_GET['category'] : 'all';

// Get the data from the session
if (isLoggedIn()) {
    $data = json_decode($_SESSION['file_data_log'], true);
}
else
{
    $data = json_decode($_SESSION['file_data'], true);
    $data = json_decode($data, true);
}

// Filter data based on sentiment, if filter is set
if ($sentiment_filter !== 'all') {
    $data = array_filter($data, function($row) use ($sentiment_filter) {
        return strtolower($row['sentiment']) === strtolower($sentiment_filter);
    });
}

// Filter data based on category, if filter is set
if ($category_filter !== 'all') {
    $data = array_filter($data, function($row) use ($category_filter) {
        return strtolower($row['category']) === strtolower($category_filter);
    });
}

// Calculate total rows and total pages after filtering
$total_rows = count($data);
$total_pages = ceil($total_rows / $rows_per_page);

// Ensure current page is within valid range
if ($current_page < 1) $current_page = 1;
if ($current_page > $total_pages) $current_page = $total_pages;

// Calculate the starting row for the current page
$start_index = ($current_page - 1) * $rows_per_page;

// Slice the data array to get only the rows for the current page
$display_data = array_slice($data, $start_index, $rows_per_page);

// Prepare response
$response = [
    'data' => $display_data,
    'current_page' => $current_page,
    'total_pages' => $total_pages
];

// Return the sliced data as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
