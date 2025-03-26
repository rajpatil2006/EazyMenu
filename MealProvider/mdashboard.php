<?php
include "../config.php";
$mId = $_GET['mId'];               
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
                <li><a href="profile/mprofile.php?mId=<?php echo $mId ?>">⚙️ Mess Details</a></li>
                <li><a href="menu/todaymenu.php?mId=<?php echo $mId ?>">🍽️ Today's Menu</a></li>
                <li><a href="hhmenu.html?mId=<?php echo $mId ?>">📜 Menu History</a></li>            
                <li><a href="../index.html" class="logout">🔒 Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="content">
        <header>
            <h1>Welcome,<?php
               $selectQuery = "SELECT * FROM `messowner` WHERE moid = $mId ";
                $Result = mysqli_query($con, $selectQuery);
                $rowM = mysqli_fetch_assoc($Result);
                
                echo $rowM['mofname'].' '.$rowM['molname']?></h1>
        </header>

        
    </main>

    <script src="mdashboard.js"></script>
</body>
</html>


