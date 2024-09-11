<?php
include('../connection.php');
include('session_detector.php');
if (!isset($_SESSION['student_id'])) {
    echo "You must be logged in to view grades.";
    exit;
}

$username = $_SESSION['student_id'];

// Get the student ID
$student_id_query = "SELECT student_id FROM student_list WHERE username = ?";
$stmt = $connection->prepare($student_id_query);
$stmt->bind_param("s", $username);
$stmt->execute();
$student_id_result = $stmt->get_result();

if ($student_id_result->num_rows > 0) {
    $student_row = $student_id_result->fetch_assoc();
    $student_id = $student_row['student_id'];

    // Fetch all academic years for dropdown
    $acadyear_query = "
        SELECT DISTINCT acadyear
        FROM grades_uploads
        ORDER BY acadyear DESC
    ";
    $acadyear_result = $connection->query($acadyear_query);

    // Fetch the latest academic year
    $latest_acadyear_query = "
        SELECT DISTINCT acadyear
        FROM grades_uploads
        ORDER BY acadyear DESC
        LIMIT 1
    ";
    $latest_acadyear_result = $connection->query($latest_acadyear_query);
    $latest_acadyear_row = $latest_acadyear_result->fetch_assoc();
    $latest_acadyear = $latest_acadyear_row['acadyear'] ?? '';

    // Fetch distinct semesters for dropdown
    $semester_query = "
        SELECT DISTINCT semester
        FROM grades_uploads
        ORDER BY semester DESC
    ";
    $semester_result = $connection->query($semester_query);

    // Fetch grades based on selected academic year and semester
    $selected_acadyear = isset($_GET['acadyear']) ? $_GET['acadyear'] : $latest_acadyear;
    $selected_semester = isset($_GET['semester']) ? $_GET['semester'] : '';

    $query = "
        SELECT
            g.course,
            g.yearlevel,
            g.subject,
            g.instructor,
            g.prelim,
            g.midterm,
            g.finals,
            g.totalave,
            g.gradescale,
            g.status,
            g.studentname
        FROM
            grades_uploads g
        WHERE
            g.studid = ?
            AND (? = '' OR g.acadyear = ?)
            AND (? = '' OR g.semester = ?)
        ORDER BY
            g.acadyear DESC,
            g.semester DESC
    ";

    $stmt = $connection->prepare($query);
    $stmt->bind_param("sssss", $student_id, $selected_acadyear, $selected_acadyear, $selected_semester, $selected_semester);
    $stmt->execute();
    $result = $stmt->get_result();

    $grades = [];
    $student_info = [];

    while ($row = $result->fetch_assoc()) {
        if (empty($student_info)) {
            $student_info = [
                'name' => $row['studentname'],
                'course' => $row['course'],
                'yearlevel' => $row['yearlevel']
            ];
        }

        $key = $row['subject'];
        if (!isset($grades[$key])) {
            $grades[$key] = [];
        }
        $grades[$key][] = $row;
    }
} else {
    echo "No student found.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        h1 {
            text-align: center;
            margin-top: 20px;
        }
/* Improved CSS for form and table layout */
.flex-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 20px;
    color: white;
    
}
.container {
    background: url('../P4.png')no-repeat center center;
    background-size: 52% 107%; /* Scales the image to fit the container exactly */
    background-position: center;
    background-color: rgba(255, 255, 255, 1);
    border-radius: 20px;
}



form {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}

label {
    font-weight: bold;
    margin-right: 10px;
    height: 1px;
}

select {
    height: 2rem;
    padding: 5px;
    font-size: 1rem;
}

input[type="submit"] {
    height: 20px;
    padding: 0 10px;
    font-size: 15px;
    cursor: pointer;
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
    border: none;
    border-radius: 5px;
    width: 35%;
    margin-bottom: 0.5px;
}
table{
    color: black;
    z-index: 5;
}
td{
    font-size: 15px;
    background-color: rgba(0, 0, 0, 0);
    
}
.student-table {
    width: 100%;
    margin: 0 auto;
    border-collapse: collapse;
    font-size: 1rem;
    text-align: center;
}

.student-table th, .student-table td {
    border: 2.5px solid #000000;
    padding: 8px;
    text-align: center;
}

.student-table th {
    background-color:rgba(0, 0, 0, 0);
}

.grades-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    
}

.grades-table th, .grades-table td {
    border: 2.5px solid #000000;
    padding: 8px;
    text-align: center;
}

.grades-table th {
 background-color: rgba(0, 0, 0, 0);
}
/* .acadfilter{
    background-color: rgba(0, 0, 0, 1);
} */
body {
            margin: 0;
            padding: 0;
        }
        
        /* Hide main content initially */
        .main-content {
            display: none;
        }
        .contents{
            background-color: rgba(0, 13, 140, 1);
            }
        /* Force landscape or desktop message */
        .force-landscape-message {
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 20px;
            border-radius: 10px;
        }
        /* .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('../PP5.png');
            background-size: cover;
            background-position: center;
            filter: blur(3px); 
            z-index: -1;
        } */
        @media screen and (min-width: 1024px) {
            /* Show content only in desktop view (or landscape on large screens) */
            .main-content {
                display: block;
            }
            .force-landscape-message {
                display: none; /* Hide message */
            }
        }
        @media screen and (max-width: 1120px) and (orientation: portrait) {
    body {
        margin: 0;
        padding: 0;
        overflow: hidden;
    }
    #logout-tab {
            background-color: rgb(205,60,60);
            bottom: 22rem;
            align-self: flex-start;
            position: absolute;
            border-radius: 1rem;
            width: 90%;
            margin-left: 18px;
            color: #fff;
    }
    }
    @media screen and (orientation: landscape) and (max-width: 1023px) {
        #logout-tab {
            background-color: rgb(205,60,60);
            bottom: 1rem;
            top: 300px;
            left: 12.5px;
            align-self: flex-start;
            position: absolute;
            border-radius: 1rem;
            width: 90%;
            margin-right: 5%;
            color: #fff;
        }
}

        @media screen and (orientation: landscape) and (max-width: 1023px) {
            /* Show content in landscape mode for smaller devices */
            .main-content {
                display: block;
            }
            .force-landscape-message {
                display: none; /* Hide message */
            }
        }
        @media screen and (orientation: landscape) and (max-width: 1024px) {
            .container {
            background: url('../P4.png')no-repeat center center;
            background-size: 100% 90%; /* Scales the image to fit the container exactly */
            background-position: center;
            background-color: rgba(255, 255, 255, 1);
            border-radius: 20px;
        }
        }

        @media screen and (max-width: 740px) {
       
            .mobile-tabs {
                display: block;
            }
        }
 
        @media screen and (min-width: 741px) {
            .mobile-tabs {
                display: none;
            }
        }
        @media screen and (max-width: 1120px) and (orientation: portrait) {
            body {
                margin: 0;
                padding: 0;
                overflow: hidden;
            }

            .whole-content, .main-content {
                display: none;
            }
            .contents{
                background-color: rgba(0, 13, 140, 1);
            }

            .force-landscape-message {
                display: block;
                position: relative;
                top: 280px;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 17px;
                font-weight: bold;
                background-color: rgba(255, 255, 255, 0.8);
                color: black;
                padding: 20px;
                height: 500px;
                border-radius: 20px;
                overflow: hidden;
            }

            .force-landscape-message h2 {
                font-size: 10rem;
                font-weight: bold;
                text-align: center;
                color: black;
                margin-bottom: 10px;
                margin-left: -30px;
                margin-top: 40px;
                overflow: hidden;
            }
        }

        @media screen and (min-width: 1120px) {
            .force-landscape-message {
                display: none;
            }

            .main-content {
                display: block;
            }
        }
@media screen and (min-width: 1120px) {
    .force-landscape-message {
        display: none; 
    }
    .table{
        font-size: 10px;
    }
    .main-content {
        display: block; 
    }
    
}
.acadfilter {
    background-color: #000000; /* Light gray background color */
    padding: 20px; /* Add some padding around the form */
    border-radius: 8px; /* Optional: round the corners of the background */
}
/* Form container styling */
.filter-form {
    background-color: rgba(0, 0, 0, 0.7);
    padding: 20px;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    width: 30%;
}

/* Form group styling */
.form-group {
    display: flex;
    flex-direction: column;
    width: 100%;
    
}
.form-group th {
    height: 10px;
}

label {
    color: black;
    font-weight: bold;
    margin-bottom: 5px;
    margin-top: -8px;
}

select {
    height: 23px;
    width: 8rem;
    padding: 1px;
    font-size: 1rem;
    border-radius: 5px;
    border: 1px solid #ccc;
    background-color: #fff;
}

/* Submit button styling */
.submit-button {
    height: 2.5rem;
    padding: 0 10px;
    font-size: 1rem;
    cursor: pointer;
    background-color: rgba(0, 0, 0, 1);
    color: white;
    border: none;
    border-radius: 5px;
}
.no-grades{
    color: white;
    font-size: 1rem;
    margin-top: 5px;
    margin-bottom: -20px;
    text-align: center;
    background-color: rgba(250, 0, 0, 0.5);
    padding: 10px;
    border-radius: 5px;
    border: 3px darkred solid;
}
.eca-title{
    height: 5px;
}
    </style>
</head>

<!-- <h1>Grades Viewing</h1> -->
<div class="force-landscape-message">
    <p style="margin-top:80px; font-size:30px;">Reminder</p>
    <p style="margin-top:30px;">Please rotate your device to landscape mode to view the content.</p>
    <br><img style="width: 100px; height: 100px" src="../P69.png" alt="">
</div>

<div class="whole-content">

<div class="container">
<div class="flex-container">
    <table class="student-table">
    <form method="GET" action="" class="filter-form">
    <div class="form-group">
        <tr>
            <th class="eca-title">ECA GRADING SYSTEM</th>
            <td><input type="submit" value="Filter" class="submit-button">
</tr></td>
<tr>
        <th><label for="acadyear">Academic Year:</label></th>
        <td><select name="acadyear" id="acadyear">
            <?php while ($acadyear_row = $acadyear_result->fetch_assoc()) { ?>
                <option value="<?php echo htmlspecialchars($acadyear_row['acadyear']); ?>" <?php echo ($selected_acadyear == $acadyear_row['acadyear']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($acadyear_row['acadyear']); ?>
                </option>
            <?php } ?>
        </select></td></tr>
    </div>   

    <div class="form-group">
    <tr>
    <th><label for="semester">Semester:</label></th>
        <td><select name="semester" id="semester">
            <?php while ($semester_row = $semester_result->fetch_assoc()) { ?>
                <option value="<?php echo htmlspecialchars($semester_row['semester']); ?>" <?php echo ($selected_semester == $semester_row['semester']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($semester_row['semester']); ?>
                </option>
            <?php } ?>
        </select></td></tr>
    </div>
    
</form>
        <tr>
            <th>Student ID:</th>
            <td><?php echo htmlspecialchars($student_id); ?></td>
        </tr>
        <tr>
            <th>Student Name:</th>
            <td><?php echo isset($student_info['name']) ? htmlspecialchars($student_info['name']) : 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Course & Section:</th>
            <td><?php echo isset($student_info['course']) ? htmlspecialchars($student_info['course']) : 'N/A'; ?> - <?php echo isset($student_info['yearlevel']) ? htmlspecialchars($student_info['yearlevel']) : 'N/A'; ?></td>
        </tr>
    </table>
</div>

<?php
if (count($grades) > 0) {
    
    echo '<div class="table-container">';
    echo '<div style="overflow-x:auto;">';
    echo '<div class="desktop-view">';
    echo '<table class="grades-table">';
    echo '<tr>
            <th>Sub.Code</th>
            <th class="instructor">Instructor</th>
            <th>Prelim</th>
            <th>Midterm</th>
            <th>Finals</th>
            <th>AVG</th>
            <th>EQ</th>
            <th>Status</th>
          </tr>';

    foreach ($grades as $subject => $rows) {
        foreach ($rows as $row) {
            echo '<tr>';
            
            // Extract and display only the "IS 8" portion
            $subject_parts = explode(' - ', $row['subject']);
            $cleaned_subject = $subject_parts[0]; // Get the part before the dash
            
            echo '<td>' . htmlspecialchars($cleaned_subject) . '</td>';
            echo '<td>' . htmlspecialchars(preg_replace('/[^A-Z.]/', '', $row['instructor'])) . '</td>';

            echo '<td>' . round($row['prelim'], 2) . '</td>';
            echo '<td>' . round($row['midterm'], 2) . '</td>';
            echo '<td>' . round($row['finals'], 2) . '</td>';
            echo '<td>' . htmlspecialchars(round($row['totalave'], 2)) . '</td>';
            echo '<td>' . htmlspecialchars($row['gradescale']) . '</td>';
            echo '<td>' . ($row['status'] == 'Incomplete' ? '<span class="incomplete">YOUR INCOMPLETE</span>' : htmlspecialchars($row['status'])) . '</td>';
            echo '</tr>';
        }
    }
    
    echo '</table>';
    echo '</div>';
    echo '</div>';
    echo '</div>';


} else {

    echo '<p class="no-grades">No grades available.</p>';


}
?>

</div>
</div>

<script>
function showTabContent(tab) {
  var contents = document.querySelectorAll('.tab-content');
  contents.forEach(function(content) {
      content.classList.remove('active');
  });
  document.getElementById(tab).classList.add('active');
}
function checkOrientation() {
    if (window.innerHeight > window.innerWidth) {
      
        document.querySelector('.force-landscape-message').style.display = 'block'; // Show message
        document.querySelector('.main-content').style.display = 'none'; // Hide main content
    } else {
      
        document.querySelector('.force-landscape-message').style.display = 'none'; // Hide message
        document.querySelector('.main-content').style.display = 'block'; // Show main content
    }
}


window.addEventListener('load', checkOrientation);
window.addEventListener('resize', checkOrientation);
</script>

</body>
</html>