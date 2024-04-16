<?php
session_start();
error_reporting(0);
include('config.php');
if (strlen($_SESSION['loggedin']) == 0) {
    header('location:/html/index.php');
} else {

    if (isset($_POST['update'])) {
        $athrid = intval($_GET['athrid']);
        $author = $_POST['author'];
        $sql = "UPDATE tblauthors SET AuthorName=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $author, $athrid);
        $stmt->execute();
        $_SESSION['updatemsg'] = "Author info updated successfully";
        header('location:authors.php');
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Library Management System | Add Author</title>
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
                    <h4 class="header-line">Add Author</h4>

                </div>

            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Author Info
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>Author Name</label>
                                    <?php
                                    $athrid = intval($_GET['athrid']);
                                    $sql = "SELECT * FROM tblauthors WHERE id=?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param('i', $athrid);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                    ?>
                                            <input class="form-control" type="text" name="author" value="<?php echo htmlentities($row['AuthorName']); ?>" required />
                                    <?php }
                                    } ?>
                                </div>

                                <button type="submit" name="update" class="btn btn-info">Update</button>

                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
   
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY 3.6.0 -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <!-- BOOTSTRAP 4 SCRIPTS  -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>

</html>
<?php } ?>
