<?php
session_start();
error_reporting(0);
include('config.php');
if (strlen($_SESSION['loggedin']) == 0) {
    header('location:/html/index.php');
} else {

    if (isset($_POST['update'])) {
        $category = $_POST['category'];
        $status = $_POST['status'];
        $catid = intval($_GET['catid']);
        $sql = "UPDATE  tblcategory SET CategoryName=?, Status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sii', $category, $status, $catid);
        $stmt->execute();
        $_SESSION['updatemsg'] = "Category updated successfully";
        header('location:categories.php');
    }
?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Library Management System | Edit Categories</title>
        <!-- BOOTSTRAP 4 CORE STYLE  -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
        <!-- FONT AWESOME 5 STYLE  -->
        <link href="assets/css/fontawesome.min.css" rel="stylesheet" />
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
                        <h4 class="header-line">Edit category</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Category Info
                            </div>

                            <div class="panel-body">
                                <form role="form" method="post">
                                    <?php
                                    $catid = intval($_GET['catid']);
                                    $sql = "SELECT * FROM tblcategory WHERE id=?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param('i', $catid);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                    ?>
                                        <div class="form-group">
                                            <label>Category Name</label>
                                            <input class="form-control" type="text" name="category" value="<?php echo htmlentities($row['CategoryName']); ?>" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="1" <?php if ($row['Status'] == 1) echo 'checked'; ?>>Active
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="0" <?php if ($row['Status'] == 0) echo 'checked'; ?>>Inactive
                                                </label>
                                            </div>
                                        </div>
                                <?php } ?>
                                        <button type="submit" name="update" class="btn btn-info">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
        <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
        <!-- CORE JQUERY  -->
        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <!-- BOOTSTRAP 4 SCRIPTS  -->
        <script src="assets/js/bootstrap.min.js"></script>
        <!-- CUSTOM SCRIPTS  -->
        <script src="assets/js/custom.js"></script>
    </body>

    </html>
<?php } ?>
