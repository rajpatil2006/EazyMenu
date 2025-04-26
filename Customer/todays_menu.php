<?php
include "../config.php";
$mId = $_GET['mId'];
session_start();
if (!isset($_SESSION['cId'])) {
    // Redirect to login if session is not set
    header("Location: signup/clogin.html");
    exit();
}
$cId = $_SESSION['cId'];   
$todayDate = date('Y-m-d');

$messId=$mId;

// Fetch Mess Details (if exists)
$messQuery = "SELECT * FROM messdetail WHERE messid = $messId";
$messResult = mysqli_query($con, $messQuery);
$messData = mysqli_fetch_assoc($messResult);
$messType = $messData ? $messData['messtype'] : "unknown";

// Fetch today's menu based on messid
$menuQuery = "SELECT * FROM menu WHERE messid = $messId AND menudate = '$todayDate'";
$menuResult = mysqli_query($con, $menuQuery);

$vegMenu = ["Breakfast" => null, "Lunch" => null, "Dinner" => null];
$nonVegMenu = ["Breakfast" => null, "Lunch" => null, "Dinner" => null];

while ($row = mysqli_fetch_assoc($menuResult)) {
    if ($row['veg_menuitemprice']) {
        $vegMenu[$row['menutype']] = $row;
    }
    if ($row['nonveg_menuitemprice']) {
        $nonVegMenu[$row['menutype']] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EazyMenu - Find Nearby Meals</title>
    <link rel="stylesheet" href="todays_menu.css">
</head>
<body>

    <!-- Header -->
    <header>
        <div class="logo">EazyMenu</div>
        <nav>
            <ul>
                <li><a href="cdashboard.php" class="profile-btn">Back to Dashboard</a></li>
                <li><a href="profile.php" class="profile-btn">Profile</a></li>
                <li><a href="logout.php" class="login-btn">Log Out</a></li>
            </ul>
        </nav>
    </header>

    <!-- Content Section -->
    <section class="content">

    <h2> <?php echo $messData['messname']; ?>' Todays Menu..   </h2>
    <!-- Veg Menu Cards -->
<div class="menu-container">
    <?php if ($messType == 'veg' || $messType == 'both'): ?>
        <?php foreach (["Breakfast", "Lunch", "Dinner"] as $meal): ?>
            <div class="menu-card">
                <h3>Veg <?php echo $meal; ?></h3>
                <?php if ($vegMenu[$meal]): ?>
                    <p><strong>Items:</strong></p>
                    <ul class="menu-list">
                        <?php 
                        $items = explode(',', $vegMenu[$meal]['veg_menuitemprice']);
                        foreach ($items as $item): 
                        ?>
                            <li><?php echo trim($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <p><strong>Opening:</strong> <?php echo $vegMenu[$meal]['menuotime']; ?></p>
                    <p><strong>Closing:</strong> <?php echo $vegMenu[$meal]['menuctime']; ?></p>
                    
                    

                <?php else: ?>
                    <p class="no-menu">Not Added</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Non-Veg Menu Cards -->
<div class="menu-container">
    <?php if ($messType == 'nonveg' || $messType == 'both'): ?>
        <?php foreach (["Breakfast", "Lunch", "Dinner"] as $meal): ?>
            <div class="menu-card">
                <h3>Non-Veg <?php echo $meal; ?></h3>
                <?php if ($nonVegMenu[$meal]): ?>
                    <p><strong>Items:</strong></p>
                    <ul class="menu-list">
                        <?php 
                        $items = explode(',', $nonVegMenu[$meal]['nonveg_menuitemprice']);
                        foreach ($items as $item): 
                        ?>
                            <li><?php echo trim($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <p><strong>Opening:</strong> <?php echo $nonVegMenu[$meal]['menuotime']; ?></p>
                    <p><strong>Closing:</strong> <?php echo $nonVegMenu[$meal]['menuctime']; ?></p>
                    
                    
                <?php else: ?>
                    <p class="no-menu">Not Added</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


        
    </section>

    <!-- //<script src="script.js"></script> -->
</body>
</html>
