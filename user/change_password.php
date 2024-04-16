<?php
// Start the session
session_start();

// Define variables for error messages
$currentPasswordErr = $newPasswordErr = $confirmPasswordErr = "";
$currentPassword = $newPassword = $confirmPassword = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Add custom validation checks for the form data
    if (empty($currentPassword)) {
        $currentPasswordErr = 'Current Password is required.';
    }

    if (empty($newPassword)) {
        $newPasswordErr = 'New Password is required.';
    }

    if (empty($confirmPassword)) {
        $confirmPasswordErr = 'Confirm Password is required.';
    } elseif ($newPassword != $confirmPassword) {
        $confirmPasswordErr = 'New Passwords do not match.';
    }

    // If there are no errors, proceed with password change
    if (empty($currentPasswordErr) && empty($newPasswordErr) && empty($confirmPasswordErr)) {
        // Connect to the database
        require_once 'config.php';

        // Prepare a select statement for admin with the current password
        $sql = "SELECT id, firstname, lastname, email, password FROM users WHERE email = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);

            // Set parameters
            $param_email = $_SESSION["email"];

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if email exists in the admins table
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $firstname, $lastname, $email, $storedPasswordHash);
                    if ($stmt->fetch()) {
                        // Verify the current password
                        if (password_verify($currentPassword, $storedPasswordHash)) {
                            // Update the password
                            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                            $updateSql = "UPDATE users SET password = ? WHERE email = ?";
                            $updateStmt = $conn->prepare($updateSql);
                            $updateStmt->bind_param("ss", $newPasswordHash, $_SESSION["email"]);
                            if ($updateStmt->execute()) {
                                $msg = "Your password has been successfully changed.";
                                header("Location: /html/login.php");
                            } else {
                                $error = "Oops! Something went wrong. Please try again later.";
                            }
                        } else {
                            $currentPasswordErr = "The current password you entered is incorrect.";
                        }
                    }
                } else {
                    $emailErr = "No account found with that email.";
                }
            } else {
                $error = "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }

        // Close connection
        $conn->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Your custom CSS styles -->
    <style>
        /* Add your custom CSS styles here */
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <?php include('head_nav.php'); ?>
    <!-- Add your body content here -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Change Password
                    </div>
                    <div class="card-body">
                        <?php if (isset($msg)) : ?>
                            <div class="alert alert-success"><?php echo $msg; ?></div>
                        <?php endif; ?>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <label for="current_password">Current Password:</label>
                                <input type="password" id="current_password" name="current_password" class="form-control" required>
                                <span class="error"><?php echo $currentPasswordErr; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="new_password">New Password:</label>
                                <input type="password" id="new_password" name="new_password" class="form-control" required>
                                <span class="error"><?php echo $newPasswordErr; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password:</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                <span class="error"><?php echo $confirmPasswordErr; ?></span>
                            </div>
                            <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>