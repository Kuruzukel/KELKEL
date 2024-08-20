<?php
session_start();

include('../connection.php');
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_SESSION['student_id'])) {
    $session_id = $_SESSION['student_id'];

    $stmt = $pdo->prepare("DELETE FROM session_list WHERE user_id = :student_id");
    $stmt->bindParam(':student_id', $session_id);
    $stmt->execute();

    session_unset();
    session_destroy();
}

header("Location: student_login.php"); 
exit();
?>
