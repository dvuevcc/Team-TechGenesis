<?php
session_start();
error_reporting(0);
include('config.php');
if (strlen($_SESSION['loggedin']) == 0) {
    header('location:/html/index.php');
} else {
    if (isset($_POST['add'])) {
        $bookname = $_POST['bookname'];
        $category = $_POST['category'];
        $author = $_POST['author'];
        $isbn = $_POST['isbn'];
        $price = $_POST['price'];
        $bookimg = $_FILES["bookpic"]["name"];
        // get the image extension
        $extension = substr($bookimg, strlen($bookimg) - 4, strlen($bookimg));
        // allowed extensions
        $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
        // Validation for allowed extensions .in_array() function searches an array for a specific value.
        //rename the image file
        $imgnewname = md5($bookimg . time()) . $extension;
        // Code for move image into directory

        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        } else {
            move_uploaded_file($_FILES["bookpic"]["tmp_name"], "bookimg/" . $imgnewname);
            $sql = "INSERT INTO  tblbooks(BookName,CatId,AuthorId,ISBNNumber,BookPrice,bookImage) VALUES(?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('siisss', $bookname, $category, $author, $isbn, $price, $imgnewname);
            $stmt->execute();
            $lastInsertId = $stmt->insert_id;
            if ($lastInsertId) {
                echo "<script>alert('Book Listed successfully');</script>";
                echo "<script>window.location.href='books.php'</script>";
            } else {
                echo "<script>alert('Something went wrong. Please try again');</script>";
                echo "<script>window.location.href='books.php'</script>";
            }
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
    <title>Library Management System | Add Book</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- CUSTOM STYLE -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <script type="text/javascript">
        function checkisbnAvailability() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "check_availability.php",
                data: 'isbn=' + $("#isbn").val(),
                type: "POST",
                success: function (data) {
                    $("#isbn-availability-status").html(data);
                    $("#loaderIcon").hide();
                },
                error: function () {}
            });
        }
    </script>
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
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Add Book</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Book Info
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post" enctype="multipart/form-data">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Book Name<span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" name="bookname" autocomplete="off" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Category<span style="color:red;">*</span></label>
                                            <select class="form-control" name="category" required>
                                                <option value="">Select Category</option>
                                                <?php
                                                $status = 1;
                                                $sql = "SELECT * from  tblcategory where Status=?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bind_param('i', $status);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value=" . $row['id'] . ">" . $row['CategoryName'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Author<span style="color:red;">*</span></label>
                                            <select class="form-control" name="author" required>
                                                <option value="">Select Author</option>
                                                <?php
                                                $sql = "SELECT * from  tblauthors ";
                                                $result = $conn->query($sql);
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value=" . $row['id'] . ">" . $row['AuthorName'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ISBN Number<span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" name="isbn" id="isbn" required="required" autocomplete="off" onBlur="checkisbnAvailability()" />
                                            <p class="help-block">An ISBN is an International Standard Book Number. ISBN Must be unique</p>
                                            <span id="isbn-availability-status" style="font-size:12px;"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Price<span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" name="price" autocomplete="off" required="required" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Book Picture<span style="color:red;">*</span></label>
                                            <input class="form-control" type="file" name="bookpic" autocomplete="off" required="required" />
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" name="add" id="add" class="btn btn-info">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME -->
    <!-- CORE JQUERY -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<?php } ?>
