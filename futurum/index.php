<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>



<!-- <!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<style>
		.sidebar {
			position: fixed;
			top: 0;
			left: 0;
			width: 200px;
			height: 100%;
			background-color: #f1f1f1;
			overflow-x: hidden;
			padding-top: 20px;
		}

		.main {
			margin-left: 200px;
			padding: 20px;
		}
	</style>
</head>
<body>
	<div class="header">
		<div class="logo">
			<img src="logo.png">
			<h1>Futurum</h1>
		</div>
		<div class="login-logout">
			<a href="login.php">Login</a>
			<a href="logout.php">Logout</a>
		</div>
	</div>
	<div class="container">
		<div class="sidebar">
			<ul>
				<li><a href="universities.php">Universities</a></li>
				<li><a href="courses.php">Courses</a></li>
				<li><a href="application_form.php">Application form</a></li>
			</ul>
		</div>
		<div class="main">
			
		</div>
	</div>
</body>
</html> -->

<!DOCTYPE html>
<html>
<head>
	<title>Admission</title>
	<!-- <link rel="stylesheet" type="text/css" href="style.css"> -->
	<style>
		* {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		.banner {
			background-color: #333;
			color: #fff;
			display: flex;
			align-items: center;
			padding: 10px 20px;
		}

		.banner h1 {
			flex-grow: 1;
			font-size: 24px;
		}

		.buttons a {
			color: #fff;
			margin-left: 10px;
		}

		.container {
			display: flex;
			height: calc(100vh - 60px); /* subtract the banner height */
		}

		.sidebar {
			background-color: #eee;
			width: 20%;
			height: 100%;
			padding: 20px;
		}

		.sidebar ul {
			list-style: none;
		}

		.sidebar li {
			margin-bottom: 10px;
		}

		.content {
			background-color: #fff;
			width: 80%;
			height: 100%;
			padding: 20px;
			overflow-y: auto; /* add scrollbars if the content overflows */
		}
	</style>
</head>
<body>
	<div class="banner">
		<h1>Futurum Admission</h1>
		<div class="buttons">
		<?php 
			// Check if the session variable "loggedin" is set
			if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
				// User is logged in, show logout button only
				echo '<a href="logout.php">Logout</a>';
			} else {
				// User is not logged in, show login button only
				echo '<a href="login.php">Login</a>';
			}
		?>
		</div>
	</div>
	<div class="container">
		<div class="sidebar">
			<ul>
				<li><a href="universities.php">Universities</a></li>
				<li><a href="application_form.php">Application form</a></li>
				<li><a href="courses.php">Courses</a></li>
			</ul>
		</div>
		<div class="content">
			<!-- This is where the content of each page will be loaded dynamically -->
		</div>
	</div>
	<script src="script.js"></script>
</body>
</html>
