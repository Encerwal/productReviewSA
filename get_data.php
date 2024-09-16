<?php
session_start(); // Start the session

// Number of rows per page
$rows_per_page = 15;

// Get the current page from the AJAX request, if not present default to page 1
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

//get data
$data = json_decode($_SESSION['file_data'], true);
$data = json_decode($data, true);

$total_rows = count($data); // Total number of rows
$total_pages = ceil($total_rows / $rows_per_page); // Total number of pages

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
// Return the response as JSON
echo json_encode($response);
?>