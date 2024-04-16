<?php
session_start();
include('config.php');

// Check if the session variable 'loggedin' is set
if (!isset($_SESSION['loggedin']) || empty($_SESSION['loggedin'])) {
    header('location:/html/index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System | View Requests</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl7/1L_dstPt3HV5HzY0MJOTV_090QKwelHuE9QeM7" crossorigin="anonymous">
    <!-- Add Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    <!-- Add Custom CSS -->
    <style>
        /* Add your custom CSS styles here */
    </style>
</head>

<body>
    <!-- Add Navigation Bar -->
    <?php include('head_nav.php'); ?>

    <div class="container">
        <h1 class="my-4 text-center">View Requests</h1>

        <?php
        if (isset($_SESSION['msg'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['msg'] . "</div>";
            unset($_SESSION['msg']);
        }
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
        ?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>Book Name</th>
                        <th>Request Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT br.*, b.BookName FROM borrow_requests br JOIN tblbooks b ON br.isbn_number = b.ISBNNumber";
                    $query = $conn->query($sql);
                    $cnt = 1;
                    if ($query->num_rows > 0) {
                        while ($result = $query->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $cnt . "</td>";
                            echo "<td>" . $result['user_id'] . "</td>";
                            echo "<td>" . $result['BookName'] . "</td>";
                            echo "<td>" . $result['request_date'] . "</td>";
                            echo "<td>" . $result['status'] . "</td>";
                            echo "<td>";
                            if ($result['status'] == 'pending') {
                                echo "<form method='post' action='accept_request.php'>";
                                echo "<input type='hidden' name='request_id' value='" . $result['request_id'] . "'>";
                                echo "<input type='hidden' name='status' value='issued'>";
                                echo "<button type='submit' class='btn btn-success'>Approve</button>";
                                echo "</form>";
                            }
                            echo "</td>";
                            echo "</tr>";
                            $cnt++;
                        }
                    } else {
                        echo "<tr><td colspan='6'>No borrow requests found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
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
