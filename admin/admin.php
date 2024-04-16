<?php
include('config.php');
// Assuming you have a database connection established

// Query to get the count of books listed
$bookCountQuery = "SELECT COUNT(*) AS totalBooks FROM tblbooks";
$bookCountResult = mysqli_query($conn, $bookCountQuery);
$bookCount = mysqli_fetch_assoc($bookCountResult)['totalBooks'];

// Query to get the count of books not returned
$notReturnedQuery = "SELECT COUNT(*) AS notReturned FROM tblissuedbookdetails WHERE ReturnDate IS NULL";
$notReturnedResult = mysqli_query($conn, $notReturnedQuery);
$notReturnedCount = mysqli_fetch_assoc($notReturnedResult)['notReturned'];

// Query to get the count of users
$userCountQuery = "SELECT COUNT(*) AS totalUsers FROM users";
$userCountResult = mysqli_query($conn, $userCountQuery);
$userCount = mysqli_fetch_assoc($userCountResult)['totalUsers'];

// Query to get the count of authors
$authorCountQuery = "SELECT COUNT(*) AS totalAuthors FROM tblauthors";
$authorCountResult = mysqli_query($conn, $authorCountQuery);
$authorCount = mysqli_fetch_assoc($authorCountResult)['totalAuthors'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Your custom CSS styles -->
    <style>
        /* Add your custom CSS styles here */
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
                    <a class="nav-link" href="#">Dashboard</a>
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

    <!-- Main content area -->
    <div class="container mt-4">
        <h2>Welcome to the Admin Panel</h2>
        <!-- Statistics cards -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Books Listed</h5>
                        <p class="card-text"><?php echo $bookCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Books Not Returned</h5>
                        <p class="card-text"><?php echo $notReturnedCount; ?></p>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Users</h5>
                        <p class="card-text"><?php echo $userCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Authors</h5>
                        <p class="card-text"><?php echo $authorCount; ?></p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Include Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>