<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>header</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>