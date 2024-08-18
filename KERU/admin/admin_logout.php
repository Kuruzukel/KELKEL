<?php
unset($_SESSION['username']);
header("Location: admin_login.php");
exit();

?>