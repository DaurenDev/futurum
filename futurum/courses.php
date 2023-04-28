<?php
// Start session
session_start();

// Include database connection file
require_once 'db_connect.php';

// Define variable to store courses data
$courses_data = '';

// Retrieve courses data from database
$sql = 'SELECT * FROM courses';
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Loop through courses data and create table rows
    while ($row = $result->fetch_assoc()) {
        $courses_data .= '<tr>';
        $courses_data .= '<td>' . $row['name'] . '</td>';
        $courses_data .= '<td>' . $row['description'] . '</td>';
        $courses_data .= '<td>' . $row['university_id'] . '</td>';
        $courses_data .= '<td>' . $row['duration'] . '</td>';
        $courses_data .= '<td>' . $row['fee'] . '</td>';
        $courses_data .= '</tr>';
    }
} else {
    $courses_data .= '<tr><td colspan="4">No courses found.</td></tr>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Courses - Futurum</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Navigation menu -->
    <nav>
        <div class="logo">Futurum</div>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="universities.php">Universities</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="application_form.php">Application Form</a></li>
            <?php if (isset($_SESSION['username'])): ?>
            <li class="logout"><a href="logout.php">Logout</a></li>
            <?php else: ?>
            <li class="login"><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Page content -->
    <div class="content">
        <h1>Courses</h1>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>University ID</th>
                    <th>Duraition</th>
                    <th>Fee</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $courses_data; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
