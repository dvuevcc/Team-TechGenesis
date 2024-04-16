<?php
require_once("config.php");

// Check if ISBN already exists
if (!empty($_POST["isbn"])) {
    $isbn = $_POST["isbn"];
    $sql = "SELECT id FROM tblbooks WHERE ISBNNumber=?";
    $query = $conn->prepare($sql);
    $query->bind_param('s', $isbn);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        echo "<span style='color:red'> ISBN already exists with another book. .</span>";
        echo "<script>$('#add').prop('disabled',true);</script>";
    } else {
        echo "<script>$('#add').prop('disabled',false);</script>";
    }
}
?>
