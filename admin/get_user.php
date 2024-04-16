<?php 
require_once("config.php");
if(!empty($_POST["user_id"])) {
    $user_id = strtoupper($_POST["user_id"]);

    $sql = "SELECT firstname, lastname, status, email, phone FROM users WHERE user_id=?";
    $query = $conn->prepare($sql);
    $query->bind_param('s', $user_id);
    $query->execute();
    $query->store_result();
    $query->bind_result($firstname, $lastname, $status, $email, $phone);

    if($query->num_rows > 0) {
        while ($query->fetch()) {
            if($status == 0) {
                echo "<span style='color:red'> User ID Blocked </span><br />";
                echo "<b>User Name:</b> " . htmlentities($firstname . ' ' . $lastname) . "<br />";
                echo "<script>$('#submit').prop('disabled',true);</script>";
            } else {
                echo htmlentities($firstname . ' ' . $lastname) . "<br />";
                echo htmlentities($email) . "<br />";
                echo htmlentities($phone);
                echo "<script>$('#submit').prop('disabled',false);</script>";
            }
        }
    } else {
        echo "<span style='color:red'> Invalid User ID. Please enter a valid User ID.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    }
}
?>
