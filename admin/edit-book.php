<?php
session_start();
error_reporting(0);
include('config.php'); // Assuming your database connection file is named config.php

if (strlen($_SESSION['loggedin']) == 0) {
    header('location:/html/index.php');
} else {
    // Using mysqli instead of PDO
    $db = $conn;

    if (isset($_POST['update'])) {
        $bookname = $_POST['bookname'];
        $category = $_POST['category'];
        $author = $_POST['author'];
        $isbn = $_POST['isbn'];
        $price = $_POST['price'];
        $bookid = intval($_GET['bookid']);
        $sql = "UPDATE tblbooks SET BookName='$bookname', CatId='$category', AuthorId='$author', BookPrice='$price' WHERE id='$bookid'";
        $query = mysqli_query($db, $sql);
        if ($query) {
            echo "<script>alert('Book info updated successfully');</script>";
            echo "<script>window.location.href='books.php'</script>";
        } else {
            echo "<script>alert('Failed to update book info');</script>";
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
        <title>Library Management System | Edit Book</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet" />
        <!-- GOOGLE FONT -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    </head>

    <body>
        <!------MENU SECTION START-->
        <?php include('head_nav.php'); ?>
        <!-- MENU SECTION END-->
        <div class="content-wrapper">
            <div class="container">
                <div class="row pad-botm">
                    <div class="col-md-12">
                        <h4 class="header-line">Edit Book</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Book Info
                                <br>
                            </div>
                            <div class="panel-body">
                                <form role="form" method="post">
                                    <?php
                                    $bookid = intval($_GET['bookid']);
                                    $sql = "SELECT tblbooks.BookName,tblcategory.CategoryName,tblcategory.id as cid,tblauthors.AuthorName,tblauthors.id as athrid,tblbooks.ISBNNumber,tblbooks.BookPrice,tblbooks.id as bookid,tblbooks.bookImage from  tblbooks join tblcategory on tblcategory.id=tblbooks.CatId join tblauthors on tblauthors.id=tblbooks.AuthorId where tblbooks.id='$bookid'";
                                    $query = mysqli_query($db, $sql);
                                    if (mysqli_num_rows($query) > 0) {
                                        $result = mysqli_fetch_assoc($query);
                                    ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <img src="bookimg/<?php echo htmlentities($result['bookImage']); ?>" width="100">
                                                    <a href="change-bookimg.php?bookid=<?php echo htmlentities($result['bookid']); ?>">Change Book Image</a>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Book Name<span style="color:red;">*</span></label>
                                                    <input class="form-control" type="text" name="bookname" value="<?php echo htmlentities($result['BookName']); ?>" required />
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Category<span style="color:red;">*</span></label>
                                                    <select class="form-control" name="category" required="required">
                                                        <option value="<?php echo htmlentities($result['cid']); ?>"> <?php echo htmlentities($result['CategoryName']); ?></option>
                                                        <?php
                                                        $status = 1;
                                                        $sql1 = "SELECT * from  tblcategory where Status='$status'";
                                                        $query1 = mysqli_query($db, $sql1);
                                                        if (mysqli_num_rows($query1) > 0) {
                                                            while ($row = mysqli_fetch_assoc($query1)) {
                                                                if ($result['CategoryName'] == $row['CategoryName']) {
                                                                    continue;
                                                                } else { ?>
                                                                    <option value="<?php echo htmlentities($row['id']); ?>"><?php echo htmlentities($row['CategoryName']); ?></option>
                                                        <?php }
                                                            }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Author<span style="color:red;">*</span></label>
                                                    <select class="form-control" name="author" required="required">
                                                        <option value="<?php echo htmlentities($result['athrid']); ?>"> <?php echo htmlentities($result['AuthorName']); ?></option>
                                                        <?php
                                                        $sql2 = "SELECT * from  tblauthors ";
                                                        $query2 = mysqli_query($db, $sql2);
                                                        if (mysqli_num_rows($query2) > 0) {
                                                            while ($ret = mysqli_fetch_assoc($query2)) {
                                                                if ($result['AuthorName'] == $ret['AuthorName']) {
                                                                    continue;
                                                                } else { ?>
                                                                    <option value="<?php echo htmlentities($ret['id']); ?>"><?php echo htmlentities($ret['AuthorName']); ?></option>
                                                        <?php }
                                                            }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>ISBN Number<span style="color:red;">*</span></label>
                                                    <input class="form-control" type="text" name="isbn" value="<?php echo htmlentities($result['ISBNNumber']); ?>" readonly />
                                                    <p class="help-block">An ISBN is an International Standard Book Number. ISBN Must be unique</p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Price in USD<span style="color:red;">*</span></label>
                                                    <input class="form-control" type="text" name="price" value="<?php echo htmlentities($result['BookPrice']); ?>" required="required" />
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-12">
                                        <button type="submit" name="update" class="btn btn-info">Update </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
        <!-- CORE JQUERY  -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <!-- BOOTSTRAP SCRIPTS  -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <!-- CUSTOM SCRIPTS  -->
        <script src="assets/js/custom.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>

    </html>
<?php } ?>