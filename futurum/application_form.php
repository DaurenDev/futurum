<?php
// Start session
session_start();

// Include database connection file
require_once 'db_connect.php';

// Define variables and initialize with empty values
$name = $email = $phone = $resume = $cover_letter = '';
$name_err = $email_err = $phone_err = $resume_err = $cover_letter_err = '';

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
        $email = trim($_POST['email']);
    }

    // Validate phone
    if (empty(trim($_POST['phone']))) {
        $phone_err = 'Please enter your phone number.';
    } else {
        $phone = trim($_POST['phone']);
    }

    // Validate resume file
    if (empty($_FILES['resume']['name'])) {
        $resume_err = 'Please upload your resume.';
    } else {
        // Get file name
        $resume = basename($_FILES['resume']['name']);
        // Get file extension
        $ext = pathinfo($resume, PATHINFO_EXTENSION);
        // Check if file is a pdf
        if ($ext != 'pdf') {
            $resume_err = 'Please upload a PDF file for your resume.';
        } else {
            // Move file to uploads directory
            move_uploaded_file($_FILES['resume']['tmp_name'], 'uploads/' . $resume);
        }
    }

    // Validate cover letter file
    if (empty($_FILES['cover_letter']['name'])) {
        $cover_letter_err = 'Please upload your cover letter.';
    } else {
        // Get file name
        $cover_letter = basename($_FILES['cover_letter']['name']);
        // Get file extension
        $ext = pathinfo($cover_letter, PATHINFO_EXTENSION);
        // Check if file is a pdf
        if ($ext != 'pdf') {
            $cover_letter_err = 'Please upload a PDF file for your cover letter.';
        } else {
            // Move file to uploads directory
            move_uploaded_file($_FILES['cover_letter']['tmp_name'], 'uploads/' . $cover_letter);
        }
    }

    // Check input errors before inserting into database
    if (empty($name_err) && empty($email_err) && empty($phone_err) && empty($resume_err) && empty($cover_letter_err)) {

        // Prepare an insert statement
        $sql = 'INSERT INTO applications (name, email, phone, resume, cover_letter) VALUES (?, ?, ?, ?, ?)';

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('sssss', $param_name, $param_email, $param_phone, $param_resume, $param_cover_letter);

            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_phone = $phone;
            $param_resume = $resume;
            $param_cover_letter = $cover_letter;

            // Attempt to execute the prepared statement
            if ($stmt->execute()){
                // Redirect to thank you page
                header('Location: thank-you.php');
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }
            // Close statement
            $stmt->close();
        }
        // Close connection
        $mysqli->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Application Submission Form</title>
    <style>
        body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        }

        .wrapper {
        width: 80%;
        margin: 0 auto;
        padding: 50px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }

        h2 {
        margin-top: 0;
        font-size: 36px;
        text-align: center;
        color: #333;
        }

        p {
        font-size: 18px;
        color: #666;
        }

        label {
        display: block;
        margin-bottom: 8px;
        font-size: 18px;
        color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="file"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        font-size: 18px;
        border: 2px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box;
        }

        input[type="submit"],
        input[type="reset"] {
        display: block;
        width: 100%;
        padding: 12px;
        margin-top: 20px;
        font-size: 18px;
        color: #fff;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover,
        input[type="reset"]:hover {
        background-color: #0069d9;
        }

        .help-block {
        color: red;
        font-size: 14px;
        margin-top: 5px;
        }

        .has-error input[type="text"],
        .has-error input[type="email"],
        .has-error input[type="tel"],
        .has-error input[type="file"] {
        border-color: red;
        }

    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Application Submission Form</h2>
        <p>Please fill in your details and upload your resume and cover letter.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                <span class="help-block"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                <label>Phone</label>
                <input type="tel" name="phone" class="form-control" value="<?php echo $phone; ?>">
                <span class="help-block"><?php echo $phone_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($resume_err)) ? 'has-error' : ''; ?>">
                <label>Resume</label>
                <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx">
                <span class="help-block"><?php echo $resume_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($cover_letter_err)) ? 'has-error' : ''; ?>">
                <label>Cover Letter</label>
                <input type="file" name="cover_letter" class="form-control" accept=".pdf,.doc,.docx">
                <span class="help-block"><?php echo $cover_letter_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
    </div>
</body>
</html>