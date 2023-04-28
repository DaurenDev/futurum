<?php
// Start session
session_start();

// Include database connection file
require_once 'db_connect.php';

// Define variables and initialize with empty values
$name = $email = $password = $confirm_password = '';
$name_err = $email_err = $password_err = $confirm_password_err = '';

// Process form data when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validate name
    if (empty(trim($_POST['name']))) {
        $name_err = 'Please enter your name.';
    } else {
        $name = trim($_POST['name']);
    }

    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_err = 'Please enter your email.';
    } else {
        // Prepare a select statement
        $sql = 'SELECT id FROM users WHERE email = ?';

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('s', $param_email);

            // Set parameters
            $param_email = trim($_POST['email']);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $email_err = 'This email is already taken.';
                } else {
                    $email = trim($_POST['email']);
                }
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } elseif (strlen(trim($_POST['password'])) < 8) {
        $password_err = 'Password must have at least 8 characters.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = 'Please confirm your password.';
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if ($password != $confirm_password) {
            $confirm_password_err = 'Passwords do not match.';
        }
    }

    // Check input errors before inserting into database
    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

        // Prepare an insert statement
        $sql = 'INSERT INTO users (name, email, password) VALUES (?, ?, ?)';

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('sss', $param_name, $param_email, $param_password);

            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to login page
                header('location: login.php');
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <style>
        /* Style for form container */
        .form-container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background-color: #f2f2f2;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.5);
        }

        /* Style for form inputs */
        .form-input {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
        }

        .form-input label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-input input[type="text"],
        .form-input input[type="email"],
        .form-input input[type="password"] {
            padding: 10px;
            border: none;
            border-radius: 3px;
            box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.2);
            font-size: 16px;
        }

        /* Style for form errors */
        .form-error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Style for form button */
        .form-button {
            display: block;
            margin: 20px auto 0;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s ease-in-out;
        }

        .form-button:hover {
            background-color: #3e8e41;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registration Form</h2>
        <form action="register.php" method="post">
            <div class="form-input">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>">
                <span class="form-error"><?php echo $name_err; ?></span>
            </div>
            <div class="form-input">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
                <span class="form-error"><?php echo $email_err; ?></span>
            </div>
            <div class="form-input">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password">
                <span class="form-error"><?php echo $password_err; ?></span>
            </div>
            <div class="form-input">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password">
                <span class="form-error"><?php echo $confirm_password_err; ?></span>
            </div>
            <button class="form-button" type="submit">Register</button>
        </form>
    </div>
</body>
</html>

