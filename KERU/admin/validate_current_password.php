<?php
include('../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $student_id = $_SESSION['username'];
    $current_password = $_POST['current_password'];

    $stmt = $connection->prepare("SELECT password FROM admin_tbl WHERE username = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && $row['password'] === $current_password) {
        echo "Password is correct "; // No error
    } else {
        echo "Current password is incorrect.";
    }
}
?>
