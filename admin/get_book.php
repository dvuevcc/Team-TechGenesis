<?php
require_once("config.php");
if (!empty($_POST["bookid"])) {
  $bookid = $_POST["bookid"];

  $sql = "SELECT distinct tblbooks.BookName,tblbooks.id,tblauthors.AuthorName,tblbooks.bookImage,tblbooks.isIssued FROM tblbooks
  join tblauthors on tblauthors.id=tblbooks.AuthorId
       WHERE (ISBNNumber=? || BookName like ?)";
  $query = $conn->prepare($sql);
  $bookidParam = "%$bookid%";
  $query->bind_param('ss', $bookid, $bookidParam);
  $query->execute();
  $result = $query->get_result();
  if ($result->num_rows > 0) {
?>
    <div class="container">
      <div class="row">
        <?php while ($row = $result->fetch_assoc()) { ?>
          <div class="col-md-4">
            <div class="card">
              <img class="card-img-top" src="bookimg/<?php echo htmlentities($row['bookImage']); ?>" alt="Book Image">
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlentities($row['BookName']); ?></h5>
                <p class="card-text"><?php echo htmlentities($row['AuthorName']); ?></p>
                <?php if ($row['isIssued'] == '1') : ?>
                  <p class="text-danger">Book Already issued</p>
                <?php else : ?>
                  <input type="radio" name="bookid" value="<?php echo htmlentities($row['id']); ?>" required>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  <?php
  } else { ?>
    <p>Record not found. Please try again.</p>
<?php
  }
}
?>