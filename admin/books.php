<?php
session_start();
error_reporting(0);
include('config.php');
if (strlen($_SESSION['loggedin']) == 0) {
    header('location:/html/index.php');
    exit(); // Add exit to stop the script from executing further
} else {
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $sql = "DELETE FROM tblbooks WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id); // 'i' for integer
        $stmt->execute();
        $stmt->close();
        $_SESSION['delmsg'] = "Category deleted successfully";
        header('location:books.php');
        exit(); // Add exit to stop the script from executing further
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Library Management System | Manage Books</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- DATATABLE STYLE  -->
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- Google Font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    <style>
        /* Custom styles for DataTables search box */
        .dataTables_wrapper .dataTables_filter input[type="search"] {
            border-radius: 5px;
            /* Rounded corners */
            padding: 5px 10px;
            /* Padding inside the search box */
            border: 1px solid #ccc;
            /* Border color */
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
            /* Optional: Add a subtle box shadow */
            width: 300px;
            /* Optional: Set a specific width for the search box */
        }

        /* Custom styles for DataTables search box placeholder text */
        .dataTables_wrapper .dataTables_filter input[type="search"]::placeholder {
            color: #999;
            /* Placeholder text color */
        }

        /* Custom styles for DataTables pagination */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 5px 10px;
            /* Padding for the pagination buttons */
            margin-left: 2px;
            /* Margin between pagination buttons */
            border-radius: 3px;
            /* Rounded corners for pagination buttons */
            border: 1px solid #ccc;
            /* Border color for pagination buttons */
            background-color: #f8f9fa;
            /* Background color for pagination buttons */
            color: #333;
            /* Text color for pagination buttons */
        }

        /* Custom styles for DataTables pagination active button */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #007bff;
            /* Background color for active pagination button */
            color: #fff;
            /* Text color for active pagination button */
        }

        /* Custom styles for DataTables pagination button hover */
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #007bff;
            /* Background color on hover */
            color: #fff;
            /* Text color on hover */
        }
    </style>
</head>

<body>
    <!-- Navbar -->
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
                    <a class="nav-link" href="/admin/add_book.php">Add Books</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/issued_books.php">Issued Books</a>
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
    <!-- Main content area -->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Books</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Books Listing
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Book Name</th>
                                            <th>Category</th>
                                            <th>Author</th>
                                            <th>ISBN</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT tblbooks.BookName, tblcategory.CategoryName, tblauthors.AuthorName, tblbooks.ISBNNumber, tblbooks.BookPrice, tblbooks.id AS bookid, tblbooks.bookImage FROM tblbooks JOIN tblcategory ON tblcategory.id = tblbooks.CatId JOIN tblauthors ON tblauthors.id = tblbooks.AuthorId";
                                        $result = $conn->query($sql);
                                        $cnt = 1;
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) { ?>
                                                <tr class="odd gradeX">
                                                    <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                    <td class="center" width="300">
                                                        <img src="bookimg/<?php echo htmlentities($row['bookImage']); ?>" width="100">
                                                        <br /><b><?php echo htmlentities($row['BookName']); ?></b>
                                                    </td>
                                                    <td class="center"><?php echo htmlentities($row['CategoryName']); ?></td>
                                                    <td class="center"><?php echo htmlentities($row['AuthorName']); ?></td>
                                                    <td class="center"><?php echo htmlentities($row['ISBNNumber']); ?></td>
                                                    <td class="center"><?php echo htmlentities($row['BookPrice']); ?></td>
                                                    <td class="center">
                                                        <a href="edit-book.php?bookid=<?php echo htmlentities($row['bookid']); ?>"><button class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button></a>
                                                        <a href="books.php?del=<?php echo htmlentities($row['bookid']); ?>" onclick="return confirm('Are you sure you want to delete?');"><button class="btn btn-danger"><i class="fa fa-pencil"></i> Delete</button></a>
                                                    </td>
                                                </tr>
                                        <?php $cnt = $cnt + 1;
                                            }
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

    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- DATATABLE SCRIPTS  -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/custom.js"></script>
</body>

</html>