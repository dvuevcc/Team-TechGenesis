<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('config.php');

if (strlen($_SESSION['loggedin']) == 0) {
    header('location:/html/index.php');
    exit();
}

if (isset($_POST['issue'])) {
    $userid = strtoupper($_POST['user_id']);
    $bookid = $_POST['bookid'];
    $isissued = 1;

    echo "User ID: " . $userid . "<br />";
    echo "Book ID: " . $bookid . "<br />";

    // Check if user is valid
    $sql_user = "SELECT user_id FROM users WHERE user_id=?";
    $query_user = $conn->prepare($sql_user);
    $query_user->bind_param('s', $userid);
    $query_user->execute();
    $query_user->store_result();

    if ($query_user->num_rows > 0) {
        // Insert into issued book details
        $sql_insert_issue = "INSERT INTO tblissuedbookdetails(UserID, BookId) VALUES (?, ?)";
        $query_insert_issue = $conn->prepare($sql_insert_issue);
        $query_insert_issue->bind_param('ss', $userid, $bookid);
        $query_insert_issue->execute();

        // Update book status
        $sql_update_book = "UPDATE tblbooks SET isIssued=? WHERE id=?";
        $query_update_book = $conn->prepare($sql_update_book);
        $query_update_book->bind_param('ss', $isissued, $bookid);
        $query_update_book->execute();

        if ($query_insert_issue->affected_rows > 0 && $query_update_book->affected_rows > 0) {
            $_SESSION['msg'] = "Book issued successfully";
            header('location:issued_books.php');
            exit();
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again";
            header('location:issued_books.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid User ID. Please enter a valid User ID.";
        header('location:issued_books.php');
        exit();
    }
}
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Library Management System | Issue a new Book</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <script>
        // function for get user name
        function getuser() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "get_user.php",
                data: 'user_id=' + $("#user_id").val(),
                type: "POST",
                success: function(data) {
                    $("#get_user_name").html(data);
                    $("#loaderIcon").hide();
                },
                error: function() {}
            });
        }

        //function for book details
        function getbook() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "get_book.php",
                data: 'bookid=' + $("#bookid").val(),
                type: "POST",
                success: function(data) {
                    $("#get_book_name").html(data);
                    $("#loaderIcon").hide();
                },
                error: function() {}
            });
        }
    </script>
    <style type="text/css">
        .others {
            color: red;
        }
    </style>


</head>

<body>
    <!------MENU SECTION START-->
    <?php include('head_nav.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Issue a New Book</h4>

                </div>

            </div>
            <div class="row">
                <div class="col-md-10 col-sm-6 col-xs-12 col-md-offset-1">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Issue a New Book
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">

                                <div class="form-group">
                                    <label>User ID<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="user_id" id="user_id" onBlur="getuser()" autocomplete="off" required />
                                </div>

                                <div class="form-group">
                                    <span id="get_user_name" style="font-size:16px;"></span>
                                </div>


                                <div class="form-group">
                                    <label>ISBN Number or Book Title<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="bookid" id="bookid" onBlur="getbook()" required="required" />
                                </div>

                                <div class="form-group" id="get_book_name">

                                </div>
                                <button type="submit" name="issue" id="submit" class="btn btn-info">Issue Book </button>

                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>

</body>

</html>

