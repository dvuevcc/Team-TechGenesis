<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('/assets/images/lib_back.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            padding-top: 56px;
            /* Height of navbar */
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        header {
            background-color: #4285f4;
            /* New color for the navbar */
            color: white;
        }

        .navbar-toggler {
            border-color: white;
        }

        .transparent-card {
            background-color: rgba(0, 0, 0, 0.5);
            /* Transparent background color */
            padding: 20px;
            color: white;
        }

        .card-body p{
            text-align: justify;
        }
    </style>
</head>

<body>
    <header>
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
                <div class="container">
                    <a class="navbar-brand" href="#">
                        <img src="/assets/images/logo1.png" alt="Logo" height="60px">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/html/login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/html/regi.php">Registration</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <div class="container">

        <div class="content">
            <div class="card transparent-card">
                <div class="card-body">
                    <h1>Welcome to Our Library</h1>
                    <p>
                        A library management system (LMS) is an essential tool for libraries to efficiently manage their resources and provide better services to users. By automating tasks such as book management, member registration, and loan management, an LMS helps librarians save time and effort. Users benefit from features such as online catalog search, book reservations, and personalized accounts. Additionally, the system can generate reports and analytics to help librarians make informed decisions about collection development and library operations. Overall, a well-designed LMS improves the overall efficiency and effectiveness of a library, enhancing the user experience and maximizing the use of library resources.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <footer class="fixed-bottom bg-dark text-white text-center p-2">
        <p>&copy; 2024 Library Management System | Developed by Aoishwarya & Niloy</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>