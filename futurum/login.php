<?php
// Start session
session_start();


// Include database connection file
require_once 'db_connect.php';

// Define variables and initialize with empty values
$email = $password = '';
$email_err = $password_err = '';

// Process form data when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_err = 'Please enter your email.';
    } else {
        $email = trim($_POST['email']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } else {
        $password = trim($_POST['password']);
    }

    // Check input errors before attempting to login
    if (empty($email_err) && empty($password_err)) {

        // Prepare a select statement
        $sql = 'SELECT id, name, email, password FROM users WHERE email = ?';

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('s', $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if email exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $name, $email, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION['loggedin'] = true;
                            $_SESSION['id'] = $id;
                            $_SESSION['name'] = $name;
                            $_SESSION['email'] = $email;

                            if ($email === 'admin@example.com') {
                                $_SESSION['is_admin'] = true;
                            }

                            // Redirect user to welcome page
                            header('location: index.php');
                        } else {
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered is not valid.';
                        }
                    }
                } else {
                    // Display an error message if email doesn't exist
                    $email_err = 'No account found with that email.';
                }
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
    <title>Login</title>
    <style>
        body {
        background-color: #f7f7f7;
        font-family: 'Roboto', sans-serif;
        }

        h2 {
        margin-top: 50px;
        text-align: center;
        font-weight: bold;
        font-size: 36px;
        color: #555;
        }

        form {
        margin: 50px auto;
        width: 400px;
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
        }

        label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        font-size: 18px;
        color: #555;
        }

        input[type="email"],
        input[type="password"] {
        display: block;
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        background-color: #f7f7f7;
        }

        input[type="submit"] {
        display: block;
        width: 100%;
        padding: 10px;
        margin-top: 20px;
        font-size: 18px;
        font-weight: bold;
        color: #fff;
        border: none;
        border-radius: 5px;
        background-color: #2196f3;
        transition: all 0.3s ease-in-out;
        }

        input[type="submit"]:hover {
        background-color: #1976d2;
        }

        span {
        display: block;
        margin-top: 5px;
        font-size: 14px;
        color: #f44336;
        font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $email; ?>">
            <?php if (!empty($email_err)) { ?>
                <span><?php echo $email_err; ?></span>
            <?php } ?>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password">
            <?php if (!empty($password_err)) { ?>
                <span><?php echo $password_err; ?></span>
            <?php } ?>
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
    </form>
</body>
</html>
