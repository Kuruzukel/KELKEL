
<div class="container2">
<div class="container">
<form action="batch_upload.php" method="post" enctype="multipart/form-data">
    Select CSV file to upload:
    <input type="file" name="file" id="file" required>
    <input type="submit" value="Upload CSV" name="submit">
</form>

    <?php
if (isset($_GET['message'])) {
    echo "<div id='success-message' class='alert alert-success'>" . htmlspecialchars($_GET['message']) . "</div>";
}
?>
</div>
</div>