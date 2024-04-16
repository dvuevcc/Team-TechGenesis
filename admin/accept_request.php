<?php
session_start();
include('config.php');

if (strlen($_SESSION['loggedin']) == 0) {
    header('location:/html/index.php');
    exit();
}

if (isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];

    // Get book_id and user_id from borrow_requests
    $sql_select_details = "SELECT book_id, user_id FROM borrow_requests WHERE request_id=? AND status='pending'";
    $query_select_details = $conn->prepare($sql_select_details);
    $query_select_details->bind_param('i', $request_id);
    $query_select_details->execute();
    $query_select_details->store_result();
    $query_select_details->bind_result($book_id, $user_id);
    $query_select_details->fetch();

    if ($query_select_details->num_rows > 0) {
        // Update tblbooks
        $sql_update_book = "UPDATE tblbooks SET isIssued=1 WHERE id=?";
        $query_update_book = $conn->prepare($sql_update_book);
        $query_update_book->bind_param('i', $book_id);
        $query_update_book->execute();

        // Insert into tblissuedbookdetails
        $sql_insert_issue = "INSERT INTO tblissuedbookdetails(BookId, UserID, IssuesDate) VALUES (?, ?, NOW())";
        $query_insert_issue = $conn->prepare($sql_insert_issue);
        $query_insert_issue->bind_param('is', $book_id, $user_id);
        $query_insert_issue->execute();

        // Update borrow_requests status
        $sql_update_request = "UPDATE borrow_requests SET status='issued' WHERE request_id=? AND status='pending'";
        $query_update_request = $conn->prepare($sql_update_request);
        $query_update_request->bind_param('i', $request_id);
        $query_update_request->execute();

        if ($query_update_request->affected_rows > 0 && $query_update_book->affected_rows > 0) {
            $_SESSION['msg'] = "Request approved successfully";
            header('location:issued_books.php');
            exit();
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again";
            header('location:view-requests.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid request. Please try again";
        header('location:view-requests.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request. Please try again";
    header('location:view-requests.php');
    exit();
}
?>
