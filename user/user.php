<?php
session_start();
error_reporting(0);
include('config.php');
if (strlen($_SESSION['loggedin']) == 0) {
    header('location:/html/index.php');
    exit();
} else {
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Library Management System</title>
    <!-- LATEST BOOTSTRAP CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <style>
        .book-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .book-card img {
            max-width: 100px;
            display: block;
            margin: 0 auto 10px;
        }

        .book-card p {
            margin: 0;
            line-height: 1.5;
        }

        .book-card .status {
            color: red;
            font-weight: bold;
        }

        .button-container {
            text-align: center;
        }

        .button-container button {
            margin-right: 10px;
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
                <div class="row">
                    <div class="col-md-12">
                        <!-- Advanced Tables -->
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <?php
                                $user_id = $_SESSION['user_id'];
                                $sql = "SELECT tblbooks.BookName, tblcategory.CategoryName, tblauthors.AuthorName, tblbooks.ISBNNumber, tblbooks.id as bookid, tblbooks.bookImage, tblbooks.isIssued, tblissuedbookdetails.UserID FROM tblbooks JOIN tblcategory ON tblcategory.id=tblbooks.CatId JOIN tblauthors ON tblauthors.id=tblbooks.AuthorId LEFT JOIN tblissuedbookdetails ON tblbooks.id=tblissuedbookdetails.BookId AND tblissuedbookdetails.UserID='$user_id'";
                                $query = $conn->query($sql);

                                $cnt = 1;
                                if ($query->num_rows > 0) {
                                    echo '<div class="row">';
                                    while ($result = $query->fetch_assoc()) {
                                ?>
                                        <div class="col-md-4">
                                            <div class="book-card">
                                                <img src="/admin/bookimg/<?php echo htmlentities($result['bookImage']); ?>" alt="<?php echo htmlentities($result['BookName']); ?>">
                                                <h4><?php echo htmlentities($result['BookName']); ?></h4>
                                                <p><strong>Category:</strong> <?php echo htmlentities($result['CategoryName']); ?></p>
                                                <p><strong>Author:</strong> <?php echo htmlentities($result['AuthorName']); ?></p>
                                                <p><strong>ISBN:</strong> <?php echo htmlentities($result['ISBNNumber']); ?></p>
                                                <?php if ($result['UserID'] == $user_id) : ?>
                                                    <div class="button-container">
                                                        <button class="btn btn-primary">Read Online</button>
                                                        <button class="btn btn-success">Download PDF</button>
                                                    </div>
                                                <?php else : ?>
                                                    <div class="button-container">
                                                        <button class="btn btn-info">Preview</button>
                                                        <button class="btn btn-warning" onclick="sendBorrowRequest(<?php echo $result['bookid']; ?>, '<?php echo htmlentities($result['ISBNNumber']); ?>')">Request to Borrow</button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                <?php
                                        if ($cnt % 3 == 0) {
                                            echo '</div><div class="row">';
                                        }
                                        $cnt++;
                                    }
                                    echo '</div>'; // close the last row
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function sendBorrowRequest(bookId, ISBNNumber) {
            console.log('Book ID:', bookId);
            console.log('ISBN Number:', ISBNNumber);
            $.ajax({
                url: 'send_borrow_request.php',
                type: 'POST',
                data: {
                    book_id: bookId,
                    isbn_number: ISBNNumber
                },
                success: function(response) {
                    // Display a confirmation message or perform other actions
                    alert('Borrow request sent successfully!');
                },
                error: function(xhr, status, error) {
                    // Handle any errors
                    alert('Error sending borrow request. Please try again.');
                }
            });
        }
    </script>
</body>

</html>
<?php } ?>
