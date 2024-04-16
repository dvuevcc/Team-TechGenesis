<?php
session_start();
error_reporting(0);
include('config.php');
if (strlen($_SESSION['loggedin']) == 0) {
    header('location:/html/index.php');
} else {

    // code for block student    
    if (isset($_GET['inid'])) {
        $id = $_GET['inid'];
        $status = "inactive";
        $sql = "UPDATE users SET status=? WHERE id=?";
        $query = $conn->prepare($sql);
        $query->bind_param('si', $status, $id);
        $query->execute();
        header('location:regi_users.php');
    }

    //code for active students
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $status = "active";
        $sql = "UPDATE users SET status=? WHERE id=?";
        $query = $conn->prepare($sql);
        $query->bind_param('si', $status, $id);
        $query->execute();
        header('location:regi_users.php');
    }


?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Library Management System | User History</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- DATATABLE STYLE  -->
        <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
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
                        <?php $uid = $_GET['uid']; ?>
                        <h4 class="header-line">#<?php echo $uid; ?> Book Issued History</h4>
                    </div>


                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- Advanced Tables -->
                        <div class="panel panel-default">
                            <div class="panel-heading">

                                <?php echo $uid; ?> Details
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User ID</th>
                                                <th>User Name</th>
                                                <th>Email</th>
                                                <th>Mobile Number</th>
                                                <th>Issued Book</th>
                                                <th>Issued Date</th>
                                                <th>Returned Date</th>
                                                <th>Fine (if any)</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $sql = "SELECT users.id as uid, users.user_id, users.firstname, users.lastname, users.email, users.phone, tblbooks.BookName, tblissuedbookdetails.IssuesDate, tblissuedbookdetails.ReturnDate, tblissuedbookdetails.fine
                                                    FROM users
                                                    JOIN tblissuedbookdetails ON users.user_id = tblissuedbookdetails.StudentId
                                                    JOIN tblbooks ON tblissuedbookdetails.BookId = tblbooks.id
                                                    WHERE users.user_id = '$uid'";
                                            $query = $conn->query($sql);
                                            $cnt = 1;
                                            while ($result = $query->fetch_assoc()) {
                                            ?>
                                                <tr class="odd gradeX">
                                                    <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                    <td class="center"><?php echo htmlentities($result['user_id']); ?></td>
                                                    <td class="center"><?php echo htmlentities($result['firstname'] . ' ' . $result['lastname']); ?></td>
                                                    <td class="center"><?php echo htmlentities($result['email']); ?></td>
                                                    <td class="center"><?php echo htmlentities($result['phone']); ?></td>
                                                    <td class="center"><?php echo htmlentities($result['BookName']); ?></td>
                                                    <td class="center"><?php echo htmlentities($result['IssuesDate']); ?></td>
                                                    <td class="center"><?php if ($result['ReturnDate'] == '') : echo "Not returned yet";
                                                                        else : echo htmlentities($result['ReturnDate']);
                                                                        endif; ?></td>
                                                    <td class="center"><?php if ($result['ReturnDate'] == '') : echo "Not returned yet";
                                                                        else : echo htmlentities($result['fine']);
                                                                        endif;
                                                                        ?></td>


                                                </tr>
                                            <?php $cnt = $cnt + 1;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <!--End Advanced Tables -->
                    </div>
                </div>



            </div>
        </div>

        
        <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
        <!-- CORE JQUERY  -->
        <script src="assets/js/jquery-1.10.2.js"></script>
        <!-- BOOTSTRAP SCRIPTS  -->
        <script src="assets/js/bootstrap.js"></script>
        <!-- DATATABLE SCRIPTS  -->
        <script src="assets/js/dataTables/jquery.dataTables.js"></script>
        <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
        <!-- CUSTOM SCRIPTS  -->
        <script src="assets/js/custom.js"></script>
    </body>

    </html>
<?php } ?>
