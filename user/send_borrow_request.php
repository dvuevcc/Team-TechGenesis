<?php
session_start();
print_r($_SESSION);
include('config.php');

if (isset($_POST['book_id']) && isset($_POST['isbn_number'])) {
    $bookId = $_POST['book_id'];
    $ISBNNumber = $_POST['isbn_number'];
    $user_id = $_SESSION['user_id']; // Assuming you store the user ID in the session

    echo "ISBN Number: " . $ISBNNumber;
    // Insert the borrow request into the database
    $stmt = $conn->prepare("INSERT INTO borrow_requests (user_id, book_id, isbn_number, request_date, status) VALUES (?, ?, ?, NOW(), 'Pending')");
    $stmt->bind_param('sis', $user_id, $bookId, $ISBNNumber);
    $stmt->execute();
    $stmt->close();

    // Handle success or failure response
    if ($stmt) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
