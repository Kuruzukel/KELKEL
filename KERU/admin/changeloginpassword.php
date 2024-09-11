<?php
session_start();
include('../connection.php');

// Check if the admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php"); // Redirect to login if not authenticated
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $admin_id = $_SESSION['username'];

    // Check if the new password meets the length requirement
    if (strlen($new_password) < 8) {
        echo "Password must be at least 8 characters long.";
    } else {
        // Update the password in the database for the admin
        $stmt = $connection->prepare("UPDATE admin_tbl SET password = ?, first_login = 1 WHERE username = ?");
        $stmt->bind_param("ss", $new_password, $admin_id);
        if ($stmt->execute()) {
            echo "Password updated successfully.";
            header("Location: addash.php"); // Redirect to the admin dashboard
            exit;
        } else {
            echo "Error updating password. Please try again.";
        }
        $stmt->close();
    }
}

$connection->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
    *{
        margin: 0;
        box-sizing: border-box;
    }
    body{
        background-image: url(../PP3.jpg);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        position: relative;
        height: 100dvh;
        width: 100dvw;
        font-family: Arial, Helvetica, sans-serif;
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;

        overflow: hidden;
    }
    .loginCard {
        /* height: 90%; */
        width: 55rem;
        background: linear-gradient(to bottom, rgba(0, 13, 133, 0.7), rgba(175, 187, 3, 0.4));
        border-radius: 2rem;
        box-shadow: 0 2rem 1rem rgba(0, 0, 0, 0.327);

        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-block: 4rem;
        min-height: fit-content;
    }
    .title {
        font-weight: bold;
        font-size: 3.5rem;
        color: rgb(210, 224, 4);
        text-align: center;
    }
    .subtitle {
        text-align: center;
        font-weight: bold;
        font-size: 2rem;
        color: white;
        margin-top: 2rem;
    }
    .field {
        width: 20rem;
    }
    .pass.field {
        margin-top: 2rem;
    }


    .handle {
        color: #FFFFFF;
        display: flex;
        justify-content: center;
        gap: 4px;
        margin-bottom: 4px;
        /* background-color: red; */
    }

    .passwordField {
        position: relative;
        /* background-color: red; */
    }

    .eyeIcon {
        height: 1.5rem;
        width: 1.5rem;
        position: absolute;
        top: 0.5rem;
        right: 1rem;
        cursor: pointer;
    }
    .passwordField[data-isVisible="false"] .eyeIcon.open {
        display: none;
    }

    .passwordField[data-isVisible="false"] .eyeIcon.close {
        display: block;
    }

    .passwordField[data-isVisible="true"] .eyeIcon.close {
        display: none;
    }

    .passwordField[data-isVisible="true"] .eyeIcon.open {
        display: block;
    }

    
    input {
        border: none;
        outline: none;
        width: 100% ;
        height: 2.5rem;
        border-radius: 1rem;
        padding-inline: 1rem;
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

    button[type='submit'] {
        transition: 0.3s;
        background-color: black;
        height: 2.5rem;
        width: 100%;
        color: white;
        border-radius: 1rem;
        outline: none;
        border: none;
        margin-top: 2rem;
        cursor: pointer;
    }
    button[type='submit']:hover {
        transform: scale(102%);
        background-color: rgba(0, 13, 133, 1);
    }
    form {
        display: flex;
        flex-direction: column;
        /* justify-content: center; */
        align-items: center;
    }
    .logoContainer {
        height: 13rem;
        width: 13rem;
        /* background-color: red; */
        margin-top: 2rem;
        background: url(../PP5.png);
        background-size: contain;
    }

    @media screen and (max-width:920px) {
        .loginCard {
            width: 90%;
            padding-inline: 2rem;
        }
    }
    @media screen and (max-width:550px) {
        .title {
            font-size: 3rem;
        }
        .subtitle {
        text-align: center;
        font-weight: bold;
        font-size: 1.5rem;
        color: white;
        margin-top: 2rem;
     
    }
    .field {
        width: 15rem;
    }
    .user.field {
        margin-top: 2rem;
    }
    .pass.field {
        margin-top: 2rem;
    }
}
    @media screen and (max-width:465px) {
        .title {
            font-size: 2rem;
        }
        .loginCard {
            justify-content: flex-start;
            height: 95vh;
        }
        .logoContainer {
            margin-top: 1rem !important;
            height: 8rem;
            width: 8rem;
        }
        .subtitle {
        text-align: center;
        font-weight: bold;
        font-size: 1.5rem;
        color: white;
        margin-top: 2rem;
     
    }
    .field {
        width: 15rem;
    }
    .user.field {
        margin-top: 2rem;
    }
    .pass.field {
        margin-top: 2rem;
    }
    }
</style>
<body>
    <div class="loginCard">
        <p class="title">ADMIN PANEL</p>
        <p class="subtitle">Change Password</p>

        <form method="POST" action="">
            <div class="pass field">
                <div class="handle">
                    <p>New Password:</p>
                </div>
                <div class="passwordField" data-isvisible="false">
                    <input name="new_password" id="loginPass" type="password" placeholder="Enter your new password" maxlength="8" autocomplete="off" required>
                    <div class="eyeIcon open" onclick="togglePass()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="none"><path d="M24 0v24H0V0zM12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path fill="#1C1C1C" d="M12 5c3.679 0 8.162 2.417 9.73 5.901c.146.328.27.71.27 1.099c0 .388-.123.771-.27 1.099C20.161 16.583 15.678 19 12 19s-8.162-2.417-9.73-5.901C2.124 12.77 2 12.389 2 12c0-.388.123-.771.27-1.099C3.839 7.417 8.322 5 12 5m0 3a4 4 0 1 0 0 8a4 4 0 0 0 0-8m0 2a2 2 0 1 1 0 4a2 2 0 0 1 0-4"/></g></svg>
                    </div>
                    <div class="eyeIcon close" onclick="togglePass()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><path d="M24 0v24H0V0zM12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path fill="#1C1C1C" d="M2.5 9a1.5 1.5 0 0 1 2.945-.404c1.947 6.502 11.158 6.503 13.109.005a1.5 1.5 0 1 1 2.877.85a10.1 10.1 0 0 1-1.623 3.236l.96.96a1.5 1.5 0 1 1-2.122 2.12l-1.01-1.01a9.6 9.6 0 0 1-1.67.915l.243.906a1.5 1.5 0 0 1-2.897.776l-.251-.935c-.705.073-1.417.073-2.122 0l-.25.935a1.5 1.5 0 0 1-2.898-.776l.242-.907a9.6 9.6 0 0 1-1.669-.914l-1.01 1.01a1.5 1.5 0 1 1-2.122-2.12l.96-.96a10.1 10.1 0 0 1-1.62-3.23A1.5 1.5 0 0 1 2.5 9"/></g></svg>
                    </div>
                </div>
            </div>
            <button type="submit">Change Password</button>
        </form>
    </div>
    <script>

    const passField = document.querySelector('.passwordField');
    const passInput = document.getElementById('loginPass');

    function togglePass() {
        if (passField.dataset.isvisible == 'false') {
            passField.dataset.isvisible = 'true'
            passInput.type = 'text'
        } else {
            passField.dataset.isvisible = 'false'
            passInput.type = 'password'
        }
    }
</script>
</body>
</html>
