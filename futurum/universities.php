<?php
session_start();

// Include database connection file
require_once 'db_connect.php';

// Fetch all universities data from database
$sql = "SELECT * FROM universities";
$result = $mysqli->query($sql);

// Check if any data found
if ($result->num_rows > 0) {
    // Start table and table headers
    echo '<table>';
    echo '<tr><th>Name</th><th>Year of Foundation</th><th>Number of Students</th><th>Educating Language</th><th>Scholarship Number</th>';

    // Display Edit and Delete buttons only for admin users
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
        echo '<th>Actions</th>';
    }

    echo '</tr>';

    // Loop through each row of data and print it in the table
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['year_of_foundation'] . '</td>';
        echo '<td>' . $row['number_of_students'] . '</td>';
        echo '<td>' . $row['educating_language'] . '</td>';
        echo '<td>' . $row['scholarship_number'] . '</td>';

        // Display Edit and Delete buttons only for admin users
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
            echo '<td><button>Edit</button> <button>Delete</button></td>';
        }

        echo '</tr>';
    }

    // End table
    echo '</table>';
} else {
    // If no data found, display a message
    echo 'No universities found.';
}

// Close database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        /* Style for the table */
        table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
        }

        /* Style for the table headers */
        th {
        background-color: #f2f2f2;
        color: #444444;
        font-weight: bold;
        padding: 12px;
        text-align: left;
        border: 1px solid #dddddd;
        }

        /* Style for the table cells */
        td {
        background-color: #ffffff;
        color: #444444;
        padding: 12px;
        text-align: left;
        border: 1px solid #dddddd;
        }

        /* Style for alternating table rows */
        tr:nth-child(even) {
        background-color: #f2f2f2;
        }
    </style>
</head>
</html>