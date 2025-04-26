<?php
include "../config.php";
session_start();
if (!isset($_SESSION['mId'])) {
   
    header("Location: signup/mlogin.html");
    exit();
}
$mId = $_SESSION['mId'];              
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EazyMenu Dashboard</title>
    <link rel="stylesheet" href="mdashboard.css">
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <h2 class="logo">EazyMenu</h2>
        <nav>
            <ul>
                <li><a href="" class="active">📊 Dashboard</a></li>
                <li><a href="profile/mprofile.php">⚙️ Mess Details</a></li>
                <li><a href="menu/todaymenu.php">🍽️ Today's Menu</a></li>
                <li><a href="menu/hmenu.php">📜 Menu History</a></li>            
                <li><a href="logout.php" class="logout">🔒 Logout</a></li>
            </ul>
        </nav>
    </aside>
      <!-- Main Content -->
    <main class="content">
        <header>
            <h2 style="margin-left:30px; color: #d3416a;">Welcome <b style="color: #696969;"><?php
               $selectQuery = "SELECT * FROM `messowner` WHERE moid = $mId ";
                $Result = mysqli_query($con, $selectQuery);
                $rowM = mysqli_fetch_assoc($Result);
                
                echo $rowM['mofname'].' '.$rowM['molname']?> !!</b> </h2>
        </header>

        <h1 style="margin-top:20%;margin-left:25%;opacity:70%;color:black">Welcome To <span style="color:#ed6868">EazyMenu</span> Dashboard</h1>

        
    </main>

    <script src="mdashboard.js"></script>
</body>
</html>


