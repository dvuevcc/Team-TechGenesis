<?php

function getLastUserId()
{
    global $conn;
    $sql = "SELECT MAX(user_id) as max_user_id FROM users";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        return $row['max_user_id'];
    }
    return null; // Return null if no user ID is found
}
// Include the database configuration file
require_once 'config.php';

// Define variables for error messages
$firstnameErr = $lastnameErr = $emailErr = $phoneErr = $usertypeErr = $passwordErr = $confirmpasswordErr = "";

if (isset($_POST['register'])) {
    // Get the form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $usertype = $_POST['usertype'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    // Add custom validation checks for the form data
    if (empty($firstname)) {
        $firstnameErr = 'First name is required.';
    }

    if (empty($lastname)) {
        $lastnameErr = 'Last name is required.';
    }

    if (empty($email)) {
        $emailErr = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = 'Invalid email format.';
    }

    if (empty($phone)) {
        $phoneErr = 'Phone number is required.';
    }

    if (empty($usertype)) {
        $usertypeErr = 'User type is required.';
    }

    if (empty($password)) {
        $passwordErr = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $passwordErr = 'Password must be at least 8 characters long.';
    }

    if (empty($confirmpassword)) {
        $confirmpasswordErr = 'Please confirm your password.';
    } elseif ($password !== $confirmpassword) {
        $confirmpasswordErr = 'Passwords do not match.';
    }

    // If there are no errors, proceed with registration
    if (empty($firstnameErr) && empty($lastnameErr) && empty($emailErr) && empty($phoneErr) && empty($usertypeErr) && empty($passwordErr) && empty($confirmpasswordErr)) {
        // Hash the passwords
        // Get the last used user_id
        $lastUserId = getLastUserId();

        // Generate the new user_id
        // Get the last user ID
        $lastUserId = getLastUserId();

        // Set the initial user ID
        if ($lastUserId === null) {
            // Use a unique ID as the first user ID
            $user_id = uniqid('UID');
        } else {
            // Increment the last user ID to generate a new user ID
            $user_id = ++$lastUserId;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the data into the appropriate table based on user type
        if ($usertype == 'user') {
            $sql = "INSERT INTO users (firstname, lastname, email, phone, usertype, password, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        } elseif ($usertype == 'admin') {
            $sql = "INSERT INTO admin (firstname, lastname, email, phone, password) VALUES (?, ?, ?, ?, ?)";
        } else {
            // Handle other user types or show an error message
        }

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die('Prepare failed: ' . $conn->error);
        }

        if ($usertype == 'user') {
            $stmt->bind_param("sssssss", $firstname, $lastname, $email, $phone, $usertype, $passwordHash, $user_id);
        } elseif ($usertype == 'admin') {
            $stmt->bind_param("sssss", $firstname, $lastname, $email, $phone, $passwordHash);
        }

        if ($stmt->execute()) {
            // Redirect to the home page after successful registration
            header("Location: \html\login.php");
            exit();
        } else {
            die('Execute failed: ' . $stmt->error);
        }

        $stmt->close();
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
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://kit.fontawesome.com/10b8650331.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/assets/css/login.css">
    <link rel="stylesheet" href="/assets/css/regi.css">
    <style>
        /* Add your custom CSS styles here */
    </style>
</head>

<body>
    <div class="text-danger mb-3 px-3">
        <?php
        if (!empty($firstnameErr)) {
            echo $firstnameErr . '<br>';
        }
        if (!empty($lastnameErr)) {
            echo $lastnameErr . '<br>';
        }
        if (!empty($emailErr)) {
            echo $emailErr . '<br>';
        }
        if (!empty($phoneErr)) {
            echo $phoneErr . '<br>';
        }
        if (!empty($usertypeErr)) {
            echo $usertypeErr . '<br>';
        }
        if (!empty($passwordErr)) {
            echo $passwordErr . '<br>';
        }
        if (!empty($confirmpasswordErr)) {
            echo $confirmpasswordErr . '<br>';
        }
        ?>
    </div>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
                                <h6 class="mb-0 mr-4 mt-2">Sign up with</h6>
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
                                <div class="form-group col-md-6">
                                    <label for="firstname">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter your first name">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter your last name">
                                </div>
                            </div>
                            <div class="row px-3">
                                <div class="form-group col-md-6">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter a valid email address">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter your phone number">
                                </div>
                            </div>
                            <div class="row px-3">
                                <div class="form-group col-md-12">
                                    <label for="usertype">User Type</label>
                                    <select class="form-control" id="usertype" name="usertype" onchange="showOptions()">
                                        <option>Select User Type</option>
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                        <!-- <option value="faculty">Faculty</option> -->
                                    </select>
                                </div>
                                <div class="form-group col-md-12" id="optionsContainer">
                                    <!-- This container will hold the specific options based on the user type -->
                                </div>
                            </div>
                            <div class="row px-3">
                                <div class="form-group col-md-6">
                                    <labelfor="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="confirmpassword">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm password">
                                </div>
                            </div>
                            <div class="row mb-3 px-3">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input id="chk1" type="checkbox" name="agree" class="custom-control-input">
                                    <label for="chk1" class="custom-control-label text-sm">I agree to the terms and
                                        conditions</label>
                                </div>
                            </div>
                            <div class="row mb-3 px-3">
                                <button type="submit" class="btn btn-grey text-center" name="register">Register</button>
                            </div>
                            <div class="row mb-4 px-3">
                                <small class="font-weight-bold">Already have an account? <a href="/html/login.php" class="text-danger">Sign in</a></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-grey py-4">
                    <div class="row px-3">
                        <small class="ml-4 ml-sm-5 mb-2">Copyright &copy; 2024. All rights reserved.</small>
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
    </form>
</body>

</html>