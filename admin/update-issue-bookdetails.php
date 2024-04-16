<?php
session_start();
error_reporting(0);
ini_set('display_errors', 1);
include('config.php');
if (strlen($_SESSION['loggedin']) == 0) {
    header('location:/html/index.php');
} else {

    if (isset($_POST['return'])) {
        $rid = intval($_GET['rid']);
        $fine = $_POST['fine'];
        $rstatus = 1;
        $bookid = $_POST['bookid'];
        $sql = "UPDATE tblissuedbookdetails SET fine=?, RetrunStatus=? WHERE id=?;
                UPDATE tblbooks SET isIssued=0 WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('siii', $fine, $rstatus, $rid, $bookid);
        $stmt->execute();

        $_SESSION['msg'] = "Book Returned successfully";
        header('location:issued_books.php');
    }
?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Library Management System | Issued Book Details</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet" />
        <!-- GOOGLE FONT -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
        <script>
            // function for get student name
            function getuser() {
                $("#loaderIcon").show();
                jQuery.ajax({
                    url: "get_user.php",
                    data: 'user_id=' + $("#userid").val(),
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
                        <h4 class="header-line">Issued Book Details</h4>

                    </div>

                </div>
                <div class="row">
                    <div class="col-md-10 col-sm-6 col-xs-12 col-md-offset-1">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Issued Book Details
                            </div>
                            <div class="panel-body">
                                <form role="form" method="post">
                                    <?php
                                    $rid = intval($_GET['rid']);
                                    $sql = "SELECT users.id, users.firstname, users.lastname, users.email, users.phone, tblbooks.BookName, tblbooks.ISBNNumber, tblissuedbookdetails.IssuesDate, tblissuedbookdetails.ReturnDate, tblissuedbookdetails.id as rid, tblissuedbookdetails.fine, tblissuedbookdetails.RetrunStatus, tblbooks.id as bid, tblbooks.bookImage 
                                            FROM tblissuedbookdetails 
                                            JOIN users ON users.user_id=tblissuedbookdetails.UserID 
                                            JOIN tblbooks ON tblbooks.id=tblissuedbookdetails.BookId 
                                            WHERE tblissuedbookdetails.id=?";
                                    $query = $conn->prepare($sql);
                                    $query->bind_param('i', $rid);
                                    $query->execute();
                                    $result = $query->get_result();
                                    $cnt = 1;
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {               ?>

                                            <input type="hidden" name="bookid" value="<?php echo htmlentities($row['bid']); ?>">
                                            <h4>User Details</h4>
                                            <hr />
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>User ID :</label>
                                                    <?php echo htmlentities($row['id']); ?>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>User Name :</label>
                                                    <?php echo htmlentities($row['firstname'] . ' ' . $row['lastname']); ?>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>User Email Id :</label>
                                                    <?php echo htmlentities($row['email']); ?>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>User Contact No :</label>
                                                    <?php echo htmlentities($row['phone']); ?>
                                                </div>
                                            </div>



                                            <h4>Book Details</h4>
                                            <hr />

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Book Image :</label>
                                                    <img src="bookimg/<?php echo htmlentities($row['bookImage']); ?>" width="120">
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Book Name :</label>
                                                    <?php echo htmlentities($row['BookName']); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>ISBN :</label>
                                                    <?php echo htmlentities($row['ISBNNumber']); ?>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Book Issued Date :</label>
                                                    <?php echo htmlentities($row['IssuesDate']); ?>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Book Returned Date :</label>
                                                    <?php if ($row['ReturnDate'] == "") {
                                                        echo htmlentities("Not Return Yet");
                                                    } else {


                                                        echo htmlentities($row['ReturnDate']);
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Fine (in USD) :</label>
                                                    <?php
                                                    if ($row['fine'] == "") { ?>
                                                        <input class="form-control" type="text" name="fine" id="fine" required />

                                                    <?php } else {
                                                        echo htmlentities($row['fine']);
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <?php if ($row['RetrunStatus'] == 0) { ?>

                                                <button type="submit" name="return" id="submit" class="btn btn-info">Return Book </button>

                            </div>

                <?php }
                                        }
                                    } ?>
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
<?php } ?>