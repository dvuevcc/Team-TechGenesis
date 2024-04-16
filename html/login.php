<?php
// Define variables for error messages
$emailErr = $passwordErr = "";
$email = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $email = $_POST['email'];
    $password_login = $_POST['password'];

    // Add custom validation checks for the form data
    if (empty($email)) {
        $emailErr = 'Email is required.';
    }

    if (empty($password_login)) {
        $passwordErr = 'Password is required.';
    }

    // If there are no errors, proceed with login
    if (empty($emailErr) && empty($passwordErr)) {
        // Connect to the database
        require_once 'config.php';

        // Prepare a select statement for users table
        $sql = "SELECT id, firstname, lastname, email, password, user_id FROM users WHERE email = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if email exists in the users table
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $firstname, $lastname, $email, $storedPasswordHash, $user_id);
                    if ($stmt->fetch()) {
                        // Verify the password
                        if (password_verify($password_login, $storedPasswordHash)) {
                            // Password is correct, so start a session and redirect to the home page
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["firstname"] = $firstname;
                            $_SESSION["lastname"] = $lastname;
                            $_SESSION["email"] = $email;
                            $_SESSION["user_id"] = $user_id;

                            // Redirect to the appropriate dashboard
                            header("location: /user/user.php");
                            exit();
                        } else {
                            // Password is not correct, so display an error message
                            $passwordErr = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Check if email exists in the admins table
                    $sql_admin = "SELECT id, firstname, lastname, email, password FROM admin WHERE email = ?";
                    $stmt_admin = $conn->prepare($sql_admin);
                    $stmt_admin->bind_param("s", $param_email);
                    $stmt_admin->execute();
                    $stmt_admin->store_result();

                    if ($stmt_admin->num_rows == 1) {
                        $stmt_admin->bind_result($id, $firstname, $lastname, $email, $storedPasswordHash);
                        if ($stmt_admin->fetch()) {
                            // Verify the password
                            if (password_verify($password_login, $storedPasswordHash)) {
                                // Password is correct, so start a session and redirect to the admin dashboard
                                session_start();
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["firstname"] = $firstname;
                                $_SESSION["lastname"] = $lastname;
                                $_SESSION["email"] = $email;

                                // Redirect to the admin dashboard
                                header("location: /admin/admin.php");
                                exit();
                            } else {
                                // Password is not correct, so display an error message
                                $passwordErr = "The password you entered was not valid.";
                            }
                        }
                    } else {
                        // Email does not exist in the users or admins table, so display an error message
                        $emailErr = "No account found with that email.";
                    }
                    $stmt_admin->close();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
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
    <meta name="google-signin-client_id" content="YOUR_CLIENT_ID.apps.googleusercontent.com">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://kit.fontawesome.com/10b8650331.js" crossorigin="anonymous"></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <link rel="stylesheet" href="/assets/css/login.css">
    <style>
        /* Add your custom CSS styles here */
    </style>
</head>

<body>
    <div class="container-fluid px-1 px-md-5 px-lg-1 px-xl-5 py-5 mx-auto">
        <div class="card card0 border-0">
            <div class="row d-flex">
                <div class="col-lg-6">
                    <div class="card1 pb-5">
                        <div class="row">
                            <img src="/assets/images/logo1.png" class="logo">
                        </div>
                        <div class="row px-3 justify-content-center mt-4 mb-5 border-line">
                            <img src="/assets/images/library_login.jpg" class="image">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card2 card border-0 px-4 py-5">
                        <div class="row mb-4 px-3">
                            <h6 class="mb-0 mr-4 mt-2">Sign in with</h6>
                            <div class="facebook text-center mr-3" onclick="signInWithFacebook()"><i class="fa fa-facebook"></i></div>
                            <div class="linkedin text-center mr-3" onclick="signInWithLinkedIn()"><i class="fa fa-linkedin"></i></div>
                            <div class="google text-center mr-3" onclick="onSignIn"><i class="fa fa-google"></i>
                            </div>
                        </div>
                        <div class="row px-3 mb-4">
                            <div class="line"></div>
                            <small class="or text-center">Or</small>
                            <div class="line"></div>
                        </div>
                        <div class="row px-3">
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <label class="mb-1">
                                    <h6 class="mb-0 text-sm">Email Address</h6>
                                </label>
                                <input class="mb-4" type="text" name="email" id="email" placeholder="Enter a valid email address" value="<?php echo htmlspecialchars($email); ?>">
                                <?php
                                if (!empty($emailErr)) {
                                    echo '<span class="error">' . $emailErr . '</span>';
                                }
                                ?>
                                <label class="mb-1">
                                    <h6 class="mb-0 text-sm">Password</h6>
                                </label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter password">
                                <?php
                                if (!empty($passwordErr)) {
                                    echo '<span class="error">' . $passwordErr . '</span>';
                                }
                                ?>
                                <div class="row mb-3 px-3">
                                    <a href="forgot_password.php" class="text-sm">Forgot Password?</a>
                                </div>
                                <div class="row mb-3 px-3">
                                    <button type="submit" class="btn btn-grey text-center" name="login">Login</button>
                                </div>
                            </form>
                        </div>
                        <div class="row mb-4 px-3">
                            <small class="font-weight-bold">Don't have an account? <a href="/html/regi.php" class="text-danger">Register</a></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-grey py-4">
                <div class="row px-3">
                    <small class="ml-4 ml-sm-5 mb-2">Copyright &copy; 2019. All rights reserved.</small>
                    <div class="social-contact ml-4 ml-sm-auto">
                        <span class="fa fa-facebook mr-4 text-sm"></span>
                        <span class="fa fa-google-plus mr-4 text-sm"></span>
                        <span class="fa fa-linkedin mr-4 text-sm"></span>
                        <span class="fa fa-twitter mr-4 mr-sm-5 text-sm"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>