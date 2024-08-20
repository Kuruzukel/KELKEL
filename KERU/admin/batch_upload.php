<?php
session_start(); // Start the session
include('../connection.php');
include('session_detector2.php');

if (isset($_POST["submit"])) {
    $file = $_FILES['file']['tmp_name'];
    $handle = fopen($file, "r");

    if ($file == NULL) {
        echo "Please select a file to upload.";
    } else {
        $students = [];
        $grades = [];
        $unique_entries = [];

        while (($fileop = fgetcsv($handle, 1000, ",")) !== false) {
            $students[] = $fileop;
            $grades[] = $fileop;
        }

        foreach ($students as $fileop) {
            $studid = !empty($fileop[6]) ? $connection->real_escape_string($fileop[6]) : 'N/A';
            $studentname = !empty($fileop[7]) ? $connection->real_escape_string($fileop[7]) : 'Unknown';
            $yearlevel = !empty($fileop[3]) ? $connection->real_escape_string($fileop[3]) : 'N/A';
            $status = !empty($fileop[13]) ? $connection->real_escape_string($fileop[13]) : 'N/A';
            $username = substr($studid, -4);
            $password = rand(100000, 999999);

            $student_key = md5($studid . $studentname . $yearlevel . $username);

            if (!in_array($student_key, $unique_entries)) {
                $unique_entries[] = $student_key; 
                
                $student_check_query = "SELECT id FROM student_list WHERE student_id='$studid'";
                $result = $connection->query($student_check_query);
                
                if ($result->num_rows == 0) {
                    $insert_student_query = "INSERT INTO student_list (student_id, studentname, year, username, password, status) VALUES ('$studid', '$studentname', '$yearlevel', '$username', '$password', '$status')";
                    $connection->query($insert_student_query);
                }
            } else {
                echo "Duplicate student entry found and skipped.";
            }
        }

        foreach ($grades as $fileop) {
            $acadyear = !empty($fileop[0]) ? $connection->real_escape_string($fileop[0]) : 'Unknown';
            $semester = !empty($fileop[1]) ? $connection->real_escape_string($fileop[1]) : 'Unknown';
            $course = !empty($fileop[2]) ? $connection->real_escape_string($fileop[2]) : 'Unknown';

            $yearlevel = !empty($fileop[3]) ? $connection->real_escape_string($fileop[3]) : 'Unknown';
            $subject = !empty($fileop[4]) ? $connection->real_escape_string($fileop[4]) : 'Unknown';
            $instructor = !empty($fileop[5]) ? $connection->real_escape_string($fileop[5]) : 'Unknown';
            $studid = !empty($fileop[6]) ? $connection->real_escape_string($fileop[6]) : 'N/A';
            $studname = !empty($fileop[7]) ? $connection->real_escape_string($fileop[7]) : 'N/A';
            $prelim = !empty($fileop[8]) ? $connection->real_escape_string($fileop[8]) : 0;
            $midterm = !empty($fileop[9]) ? $connection->real_escape_string($fileop[9]) : 0;
            $finals = !empty($fileop[10]) ? $connection->real_escape_string($fileop[10]) : 0;
            $totalave = !empty($fileop[11]) ? $connection->real_escape_string($fileop[11]) : 0;
            $gradescale = !empty($fileop[12]) ? $connection->real_escape_string($fileop[12]) : 'N/A';
            $status = !empty($fileop[13]) ? $connection->real_escape_string($fileop[13]) : 'Unknown';

            $grades_key = md5($acadyear . $semester . $course . $yearlevel . $subject . $instructor . $studid . $studname . $prelim . $midterm . $finals . $totalave . $gradescale . $status);

            if (!in_array($grades_key, $unique_entries)) {
                $unique_entries[] = $grades_key; 
                
                $student_id_query = "SELECT id FROM student_list WHERE student_id='$studid'";
                $student_id_result = $connection->query($student_id_query);
                if ($student_id_result->num_rows > 0) {
                    $student_row = $student_id_result->fetch_assoc();
                    $student_list_id = $student_row['id'];

                    $insert_grades_query = "INSERT INTO grades_uploads (acadyear, semester, course, yearlevel, subject, instructor, studid, studentname, prelim, midterm, finals, totalave, gradescale, status) VALUES ('$acadyear', '$semester', '$course', '$yearlevel', '$subject', '$instructor', '$studid', '$studname', '$prelim', '$midterm', '$finals', '$totalave', '$gradescale', '$status')";
                    if (!$connection->query($insert_grades_query)) {
                        echo "Error: " . $connection->error;
                    }
                }
            } else {
                echo "Duplicate grades entry found and skipped.";
            }
        }

        $message = urlencode("CSV file successfully imported.");
header("Location: addash.php?page=grades&message=$message");
        exit(); // Ensure no further code is executed after the redirect
    }

    fclose($handle); 
}

$connection->close();
?>
