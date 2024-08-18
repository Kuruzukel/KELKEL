<?php
include('../connection.php');

$sql = "SELECT student_id, studentname, year, username, password, status FROM student_list";
$result = $connection->query($sql);
?>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            overflow-y: auto;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
    <div class="container2">
    <div class="container">
    <table>
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Year</th>
            <th>Username</th>
            <th>Password</th>
            <th>Status</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["student_id"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["studentname"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["year"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["password"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No records found</td></tr>";
        }
        ?>
    </table>
    </div>
    </div>
    <?php
    // Close connection
    $connection->close();
    ?>
