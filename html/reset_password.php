<?php
// Include config file
require_once 'config.php';

// Check if email is set in the URL parameters
if (!isset($_GET['email'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_GET['email'];

// Define variables for error messages and success message
$passwordErr = $confirmPasswordErr = "";
$new_password = $confirm_password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Add custom validation checks for the form data
    if (empty($new_password)) {
        $passwordErr = 'New password is required.';
    } elseif (strlen($new_password) < 6) {
        $passwordErr = 'Password must be at least 6 characters long.';
    }

    if (empty($confirm_password)) {
        $confirmPasswordErr = 'Confirm password is required.';
    } elseif ($new_password !== $confirm_password) {
        $confirmPasswordErr = 'Passwords do not match.';
    }

    // If there are no errors, update the password
    if (empty($passwordErr) && empty($confirmPasswordErr)) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password for the user with the given email in the database
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $hashed_password, $email);
            if ($stmt->execute()) {
                $success = "Password has been reset successfully.";
            } else {
                echo "Error updating password: " . $conn->error;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Add your custom CSS styles here */
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <?php if (isset($success)) { echo '<div class="alert alert-success">' . $success . '</div>'; } ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?email=' . $email; ?>">
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" class="form-control" id="new_password" name="new_password">
                <span class="text-danger"><?php echo $passwordErr; ?></span>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                <span class="text-danger"><?php echo $confirmPasswordErr; ?></span>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>
</body>
</html>
