<?php 
ob_start();
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sudent Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <link rel="stylesheet" href="../styles.css">
    <style>
        
        * {
            box-sizing: border-box;
            margin: 0;
        }
        body {
            background-color: rgb(50,100,150);
            display: flex;
            flex-direction: column;
            max-height: 100vh;
        }
        header {
            margin-top: 8px;
            background: linear-gradient(to right, rgba(0, 13, 133, 0.733), rgba(175, 187, 3, 0.9));
            height: 20rem;
            width: calc(100% - 1rem);
            align-self: center;
            border-radius: 12px;
            display: flex;
            align-items: center;
            padding-inline-start: 1.5rem;
            overflow: hidden;
            
        }
        .menu-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 2rem;
            width: 3rem;
            padding-inline: 12px;
            background-color: #fff;
            border-radius: 8px;
            fill: rgb(134, 135, 147);
            cursor: pointer;
        }
        .menu-btn div {
            display: inherit;
            justify-content: inherit;
            align-items: inherit;
            height: 100%;
            width: 100%;
        }
        .menu-container {
            display: flex;
            align-items: center;
            color: rgb(210, 224, 4);
            font-size: 35px;
        }
        .hidden {
            display: none !important;
        }
        main {
            align-self: center;
            width: calc(100% - 1rem);
            min-height: calc(100vh - 5.5rem);
            margin-top: 0.5rem;
            border-radius: 12px;
            display: flex;
        }

        .sidebar {
            min-height: 100%;
            height: 2000px;
            min-width: 15rem;
            width: 15rem;
            background: linear-gradient(to bottom, rgba(0, 13, 133, 0.733), rgba(175, 187, 3, 0.9));
            border-radius: 12px;
            transition: 300ms;
            margin-right: 10px;
            display: flex;
            flex-direction: column;
            align-items: right;
            overflow: hidden;
            position: relative;
        }
        .sidebar.closed {
            min-width: 0;
            width: 0;
            margin-right: 0;
        }
        .sidebar.closed .logoadmin
         {
         left: -200px;
        opacity: 0;
        }
        
        .sidebar .logoadmin {
            padding: 1rem;
            align-self: center;
            transition: 300ms;
            margin-left: -6px;
        }
    
        .line {
            position: relative;;
        }
        .line::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 2px;
            background-color: #fff;
            margin-top: -0.7rem;

        }
        
        a.tab {
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            padding-block: 0.8rem;
            text-align: center;
            padding-right: 1.5rem;
            padding-left: 2rem;
            border-radius: 0;
            /* background-color: #fff; */
            position: relative;
            /* margin-bottom: 0.2rem; */
            text-wrap: nowrap;
            cursor: pointer;
            font-size: 15px;
            transition: 300ms;
            width: 100%;
            color: #fff;
        }
        .sidebar.closed a.tab {
            width: 0;
        }
        a.tab.active {
            background-color: #585e69;
            z-index: 10;
            /* color: #fff; */
        }
        a.tab.active::before {
            content: "";
            height: 0rem;
            width: 4rem;
            position: absolute;
            right: 0;
            bottom: 100%;
            border-radius: 4rem;
            box-shadow: 4px 4px 0 -2px #585e69;

        }
        a.tab.active::after {
            content: "";
            height: 0rem;
            width: 4rem;
            position: center;
            right: 0;
            top: 100%;
            border-radius: 4rem;
            box-shadow: 4px -4px 0 -2px #585e69;
        }
        #logout-tab {
            background-color: rgb(255,60,60);
            bottom: 104rem;
            align-self: flex-end;
            position: absolute;
            border-radius: 1rem;
            width: 90%;
            margin-right: 5%;
            color: #fff;
        }

        .contents {
            min-height: 100%;
            width: 100%;
            background-color: #fff;
            border-radius: 12px;
            padding-inline: 2rem;
            padding-block-start: 1rem;
            overflow: scroll;
            overflow-y: auto;
        }
        h2 {
            width: 20rem;
        }
        .scroll-container {
      width: 100%;
      height: 2000px; 
      overflow: scroll; 
      box-sizing: border-box;
    }
    
    
header h3 {
    font-size: 2rem;
}

        @media screen and (max-width: 500px) {
            header h3 {
                font-size: 1.4rem;
                
                
            }
            .sidebar {
                min-width: min(100rem, 90vw);
                width: 100rem;
                height: 688px;
            }
            .sidebar.closed {
                min-width: 0;
                width: 0;
            }
            .contents {
                min-width: -100px;
                width: 100%;
            }
            
            .logoadmin{
                top: 90px;
                left: 125px;
                height: 200px;
                width: 200px;
            transition: 300ms;

            }
            a{
            text-decoration: none;
            color: black;

}
        .menu-container {
            display: flex;
            align-items: center;
            color: rgb(210, 224, 4);
            font-size: 14px;

}
.menu-btn {
    width: 2rem;
    height: 2rem;
        }
        .scroll-container {
        height: 688px; /* Adjust height for smaller screens */
      }
      header {
            margin-top: 8px;
            background: linear-gradient(to right, rgba(0, 13, 133, 0.733), rgba(175, 187, 3, 0.6));
            background-color: rgba(0, 13, 133, 0.733);
            width: calc(100% - 1rem);
            align-self: center;
            border-radius: 12px;
            display: flex;
            align-items: center;
            padding-inline-start: 1.5rem;
            
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
            bottom: 30rem;
            align-self: flex-start;
            position: absolute;
            border-radius: 1rem;
            width: 90%;
            margin-right: 5%;
            color: #fff;
        }
}
    </style>
</head>

<body>
    <header>
        <div class="menu-container">

        <div class="menu-btn">
            <div class="hamburger-menu-ico">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M3 18v-2h18v2zm0-5v-2h18v2zm0-5V6h18v2z" />
                </svg>
               
            </div>
            <div class="close-ico hidden">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M6.4 19L5 17.6l5.6-5.6L5 6.4L6.4 5l5.6 5.6L17.6 5L19 6.4L13.4 12l5.6 5.6l-1.4 1.4l-5.6-5.6z" />
                </svg>
            </div>
        </div>
        <h3 style="margin-left: 1rem; text-wrap: nowrap; transition: 300ms;">STUDENT DASHBOARD</h3>
        </div>
    </header>
    <main>
        <div class="sidebar closed">
        <img src="../P2.png" alt="Logo" class="logoadmin">
        <div class="line"></div>
            <a href="Studdash.php?page=grades" class="tab" id="grades-tab"><i class="fas fa-graduation-cap"></i> Grades Viewing</a>
            <a href="Studdash.php?page=password" class="tab" id="password-tab"><i class="fas fa-key"></i> Change my password</a>
            <a href="student_logout.php" class="tab" id="logout-tab"><i class="fas fa-sign-out-alt"></i> Logout</a>

        </div>
        <div class="scroll-container" id="scrollContainer">
        <div class="contents" id="content">
            <?php
            $page = isset($_GET['page']) ? $_GET['page'] : 'gradesviewing';
            switch ($page) {
  
                case 'grades':
                    include('gradesviewing.php');
                    break;
                case 'password':
                    include('changemypass.php');
                    break;
                default:
                    include('gradesviewing.php');
                    break;
            }
            ?>
        </div>
        </div>
    </main>
    <script>
        const menuBtn = document.querySelector('.menu-btn')
        const hamburgerIcon = document.querySelector('.hamburger-menu-ico')
        const closeIcon = document.querySelector('.close-ico')
        const sidebar = document.querySelector('.sidebar')
        
        menuBtn.addEventListener('click', () => {
            if (hamburgerIcon.classList.contains('hidden')) {
                hamburgerIcon.classList.remove('hidden')
                closeIcon.classList.add('hidden')
                sidebar.classList.add('closed')
            } else {
                hamburgerIcon.classList.add('hidden')
                closeIcon.classList.remove('hidden')
                sidebar.classList.remove('closed')
            }
        });

        
        const urlParams = new URLSearchParams(window.location.search);
        const page = urlParams.get('page') || 'dashboard';
        document.querySelector(`#${page}-tab`).classList.add('active');
        
        function scrollToBottom() {
      var container = document.getElementById('scrollContainer');
      container.scrollTop = container.scrollHeight;
    }
    </script>
</body>
</html>