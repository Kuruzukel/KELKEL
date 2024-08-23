<?php

include('../connection.php');
include('session_detector.php');
// Redirect to login if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_SESSION['student_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the stored plaintext password
    $stmt = $connection->prepare("SELECT password FROM student_list WHERE username = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && $current_password === $row['password']) {
        // Check if new passwords match
        if ($new_password === $confirm_password) {
            // Validate password strength (minimum 8 characters)
            if (strlen($new_password) >= 8) {
                // Update plaintext password
                $stmt = $connection->prepare("UPDATE student_list SET password = ? WHERE username = ?");
                $stmt->bind_param("ss", $new_password, $student_id);
                if ($stmt->execute()) {
                    $success_message = "Password changed successfully.</a>.";
                } else {
                    $error_message = "Error updating password. Please try again.";
                }
            } else {
                $error_message = "New password must be at least 8 characters long.";
            }
        } else {
            $error_message = "New password and confirm password do not match.";
        }
    } else {
        $error_message = "Current password is incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
         .contents{
            background-color: rgba(0, 13, 140, 1);

        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
        .password-strength {
            font-size: 0.9em;
            margin-top: 5px;
        }
        .strength-weak {
            color: red;
        }
        .strength-medium {
            color: orange;
        }
        .strength-strong {
            color: green;
        }
        .correct-pass {
            color: green;
        }
        .incorrect-pass {
            color: red;
        }
        .toggle-password {
            cursor: pointer;
            position: absolute;
            margin-left: -30px;
            margin-top: 10px;
            color: black;
        }
        .container {
            position: relative;
            max-width: 100%;
            background-color: rgba(255, 255, 255, 0.6);
            color: black;
            text-align: center;
            border-radius: 20px;
        }
        .password-wrapper {
            position: relative;
        }
        input[type="password"] {
            padding-right: 30px;
            
        }

        input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    input[type=number] {
        appearance: textfield;
      -moz-appearance: textfield;
    }
    button{
        
        transition: 0.3s;
        background-color: rgba(0, 0, 0, 0.8);
        height: 4rem;
        width: 30%;
        color: white;
        border-radius: 1rem;
        outline: none;
        border: none;
        margin-top: 25px;
        cursor: pointer;
        margin-bottom: 30px;
    }
    button:hover{
        transform: scale(102%);
        background-color: rgba(0, 13, 133, 1);
    }
        label {
            font-weight: bold;
        }
        @media screen and (max-width:550px) {
            button {
                font-size: 10px;
                width: 80%;
            }
            input {
                width: 170px;
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
        @media screen and (max-width: 1120px) and (orientation: portrait) {
            body {

                
            }
            .toggle-password {
            cursor: pointer;
            position: absolute;
            margin-left: -25px;
            margin-top: 10px;
        }
        }
        /* .error{
    color: white;
    font-size: 1rem;

    text-align: center;
    background-color: rgba(250, 0, 0, 0.2);
    padding: 10px;
    border-radius: 5px;
    border: 3px darkred solid;
    opacity:  1;
    transition: opacity 1s ease-in-out; 
    
} */
    </style>
</head>
<body>

<div class="container">
    <form method="POST" action="">
        <label for="current_password">Current Password:</label>
        <div class="password-wrapper">
            <input type="text" id="current_password" name="current_password" maxlength="8" required>
        </div>
        <span id="current-password-error" class="error"><?php if (isset($error_message)) { echo $error_message; } ?></span><br>

        <label for="new_password">New Password:</label>
        <div class="password-wrapper">
            <input type="password" id="new_password" name="new_password" maxlength="8" required>
            <i class="fas fa-eye toggle-password" id="toggle-new-password"></i>
        </div>
        <div id="password-strength" class="password-strength"></div><br>

        <label for="confirm_password">Re-type New Password:</label>
        <div class="password-wrapper">
            <input type="password" id="confirm_password" name="confirm_password"maxlength="8" required>
            <i class="fas fa-eye toggle-password" id="toggle-confirm-password"></i>
        </div>
        <span id="confirm-password-error" class="error"></span><br>
        <button type="submit">Change Password</button>
        <?php if (isset($success_message)) { echo "<p class='success'>$success_message</p>"; } ?>
    </form>
</div>
   
<script>
document.getElementById('current_password').addEventListener('input', function() {
    let currentPassword = this.value;
    let errorElement = document.getElementById('current-password-error');

    fetch('validate_current_password.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'current_password=' + encodeURIComponent(currentPassword)
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes("Password is correct")) {
            errorElement.textContent = data;
            errorElement.className = 'success';
        } else {
            errorElement.textContent = data;
            errorElement.className = 'error';
        }
    });
});


document.getElementById('new_password').addEventListener('input', function() {
    let password = this.value;
    let strengthElement = document.getElementById('password-strength');

    let strength = 'Weak';
    if (password.length >= 8) {
        const hasUpperCase = /[A-Z]/.test(password);
        const hasLowerCase = /[a-z]/.test(password);
        const hasDigit = /[0-9]/.test(password);
        const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

        if (hasUpperCase && hasLowerCase && hasDigit && hasSpecialChar) {
            strength = 'Strong';
        } else if ((hasUpperCase && hasLowerCase) || (hasUpperCase && hasDigit) || (hasLowerCase && hasDigit)) {
            strength = 'Medium';
        }
    }

    strengthElement.textContent = 'Password Strength: ' + strength;
    strengthElement.className = 'password-strength strength-' + strength.toLowerCase();
});

document.getElementById('confirm_password').addEventListener('input', function() {
    let confirmPassword = this.value;
    let newPassword = document.getElementById('new_password').value;
    let errorElement = document.getElementById('confirm-password-error');

    if (confirmPassword !== newPassword) {
        errorElement.textContent = "Passwords do not match.";
        errorElement.className = 'incorrect-pass';
    } else {
        errorElement.textContent = "Password match";
        errorElement.className = 'correct-pass';
    }
});

function togglePasswordVisibility(inputId, iconId) {
    let passwordField = document.getElementById(inputId);
    let icon = document.getElementById(iconId);
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        passwordField.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}


document.getElementById('toggle-new-password').addEventListener('click', function() {
    togglePasswordVisibility('new_password', 'toggle-new-password');
});

document.getElementById('toggle-confirm-password').addEventListener('click', function() {
    togglePasswordVisibility('confirm_password', 'toggle-confirm-password');
});
</script>
</body>
</html>
