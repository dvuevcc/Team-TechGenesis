<?php
session_start();
error_reporting(0);
include('config.php');
if (strlen($_SESSION['loggedin']) == 0) {
    header('location:/html/index.php');
    exit();
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Library Management System | Manage Issued Books</title>
    <!-- BOOTSTRAP 4 CORE STYLE  -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- DATATABLE STYLE  -->
    <link href="assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

</head>

<body>
    <!------MENU SECTION START-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="/assets/images/logo1.png" alt="Logo" height="40">
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/admin/admin_profile.php"><i class="fas fa-user"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/admin/admin.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/regi_users.php">Users</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Management
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/admin/books.php">Books</a>
                        <a class="dropdown-item" href="/admin/authors.php">Authors</a>
                        <a class="dropdown-item" href="/admin/categories.php">Categories</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/issued_books.php">Issued Books</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/issue-book.php">Issue Book</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/view-requests.php">View Requests</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/change_password.php">Change Password</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/html/logout.php">Logout</a>
                </li>
            </ul>

        </div>
    </nav>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Issued Books</h4>
                </div>
                <div class="row">
                    <?php if (!empty($_SESSION['error'])) { ?>
                        <div class="col-md-6">
                            <div class="alert alert-danger">
                                <strong>Error :</strong>
                                <?php echo htmlentities($_SESSION['error']); ?>
                                <?php $_SESSION['error'] = ""; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (!empty($_SESSION['msg'])) { ?>
                        <div class="col-md-6">
                            <div class="alert alert-success">
                                <strong>Success :</strong>
                                <?php echo htmlentities($_SESSION['msg']); ?>
                                <?php $_SESSION['msg'] = ""; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (!empty($_SESSION['delmsg'])) { ?>
                        <div class="col-md-6">
                            <div class="alert alert-success">
                                <strong>Success :</strong>
                                <?php echo htmlentities($_SESSION['delmsg']); ?>
                                <?php $_SESSION['delmsg'] = ""; ?>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="card">
                        <div class="card-header">
                            <strong>Issued Books</strong>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User Name</th>
                                            <th>Book Name</th>
                                            <th>ISBN </th>
                                            <th>Issued Date</th>
                                            <th>Return Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT users.firstname AS FullName, tblbooks.BookName, tblbooks.ISBNNumber, tblissuedbookdetails.IssuesDate, tblissuedbookdetails.ReturnDate, tblissuedbookdetails.id AS rid FROM tblissuedbookdetails JOIN users ON users.user_id = tblissuedbookdetails.UserID JOIN tblbooks ON tblbooks.id = tblissuedbookdetails.BookId ORDER BY tblissuedbookdetails.id DESC";
                                        $result = $conn->query($sql);
                                        $cnt = 1;
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                        ?>
                                                <tr class="odd gradeX">
                                                    <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                    <td class="center"><?php echo htmlentities($row['FullName']); ?></td>
                                                    <td class="center"><?php echo htmlentities($row['BookName']); ?></td>
                                                    <td class="center"><?php echo htmlentities($row['ISBNNumber']); ?></td>
                                                    <td class="center"><?php echo htmlentities($row['IssuesDate']); ?></td>
                                                    <td class="center"><?php echo ($row['ReturnDate'] == "") ? htmlentities("Not Return Yet") : htmlentities($row['ReturnDate']); ?></td>
                                                    <td class="center">
                                                        <a href="update-issue-bookdetails.php?rid=<?php echo htmlentities($row['rid']); ?>" class="btn btn-primary"><i class="fa fa-edit "></i> Edit</a>
                                                    </td>
                                                </tr>
                                        <?php
                                                $cnt++;
                                            }
                                        }
                                        ?>
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
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <!-- BOOTSTRAP 4 SCRIPTS  -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- DATATABLE SCRIPTS  -->
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap4.min.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>

</html>