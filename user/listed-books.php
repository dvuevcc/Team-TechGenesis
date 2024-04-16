<?php
session_start();
error_reporting(0);
include('config.php');
if (strlen($_SESSION['loggedin']) == 0) {
    header('location:/html/index.php');
} else {
?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Library Management System | Issued Books</title>
        <!-- LATEST BOOTSTRAP CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet" />
        <!-- GOOGLE FONT -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    </head>

    <body>
        <!------MENU SECTION START-->
        <!-- MENU SECTION END-->
        <div class="content-wrapper">
            <div class="container">
                <div class="row pad-botm">
                    <div class="col-md-12">
                        <h4 class="header-line">Manage Issued Books</h4>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <!-- Advanced Tables -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Issued Books
                                </div>
                                <div class="panel-body">


                                    <?php
                                    $sql = "SELECT tblbooks.BookName, tblcategory.CategoryName, tblauthors.AuthorName, tblbooks.ISBNNumber, tblbooks.id as bookid, tblbooks.bookImage, tblbooks.isIssued FROM tblbooks JOIN tblcategory ON tblcategory.id=tblbooks.CatId JOIN tblauthors ON tblauthors.id=tblbooks.AuthorId";
                                    $query = $conn->query($sql);
                                    $cnt = 1;
                                    if ($query->num_rows > 0) {
                                        while ($result = $query->fetch_assoc()) {
                                    ?>
                                            <div class="col-md-4" style="float:left; height:300px;">
                                                <img src="/admin/bookimg/<?php echo htmlentities($result['bookImage']); ?>" width="100">
                                                <br /><b><?php echo htmlentities($result['BookName']); ?></b><br />
                                                <?php echo htmlentities($result['CategoryName']); ?><br />
                                                <?php echo htmlentities($result['AuthorName']); ?><br />
                                                <?php echo htmlentities($result['ISBNNumber']); ?><br />
                                                <?php if ($result['isIssued'] == '1') : ?>
                                                    <p style="color:red;">Book Already issued</p>
                                                <?php endif; ?>
                                            </div>
                                    <?php
                                            $cnt = $cnt + 1;
                                        }
                                    }
                                    ?>

                                </div>
                            </div>
                            <!--End Advanced Tables -->
                        </div>
                    </div>



                </div>
            </div>
        </div>

        <!-- CONTENT-WRAPPER SECTION END-->
        <?php include('includes/footer.php'); ?>
        <!-- FOOTER SECTION END-->
        <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
        <!-- CORE JQUERY  -->
        <script src="assets/js/jquery-1.10.2.js"></script>
        <!-- BOOTSTRAP SCRIPTS  -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <!-- CUSTOM SCRIPTS  -->
        <script src="assets/js/custom.js"></script>

    </body>

    </html>
<?php } ?>