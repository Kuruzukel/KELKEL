<?php
session_start();

// Dummy data for demonstration
$users = [
    '12345' => 'password123',
    '67890' => 'password456'
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    if (isset($users[$student_id]) && $users[$student_id] === $old_password) {
        // Update the password (in a real application, you would update the database)
        $users[$student_id] = $new_password;
        echo "Password changed successfully for Student ID: $student_id";
        // Save the updated users array (in a real application, save changes to the database)
    } else {
        echo "Invalid Student ID or Old Password.";
    }
}
?>
