<?php
include "../../config.php";
session_start();
if (!isset($_SESSION['mId'])) {
    // Redirect to login if session is not set
    header("Location: ../signup/mlogin.html");
    exit();
}
$mId = $_SESSION['mId'];  
$todayDate = date('Y-m-d');

// Fetch Mess Owner Details (including messid)
$selectQuery = "SELECT * FROM messowner WHERE moid = $mId";
$Result = mysqli_query($con, $selectQuery);
$rowM = mysqli_fetch_assoc($Result);

$messId = $rowM ? $rowM['messid'] : 0;

// Fetch Mess Details (if exists)
$messQuery = "SELECT * FROM messdetail WHERE messid = $messId";
$messResult = mysqli_query($con, $messQuery);
$messData = mysqli_fetch_assoc($messResult);
$messType = $messData ? $messData['messtype'] : "unknown";


$menuQuery = "SELECT * FROM menu WHERE messid = $messId ORDER BY menudate DESC";
$menuResult = mysqli_query($con, $menuQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EazyMenu History Menu</title>
    <link rel="stylesheet" href="hmenu.css">
   
</head>
<body>
    <aside class="sidebar">
        <h2 class="logo">EazyMenu</h2>
      
        <nav>
            <ul>
                <li><a href="../mdashboard.php">📊 Dashboard</a></li>
                <li><a href="../profile/mprofile.php">⚙️ Mess Details</a></li>
                <li><a href="todaymenu.php" >🍽️ Today's Menu </a></li>
                <li><a href="" class="active">📜 Menu History</a></li>            
                <li><a href="../logout.php" class="logout">🔒 Logout</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="content">
        <header>
            <h2 style="margin-left:30px; color: #d3416a;">Welcome <b style="color: #696969;"><?php echo $rowM['mofname'].' '.$rowM['molname']; ?> !!</b></h2>
        </header>
   
    <section>
    <h4 style="margin-left:30px;margin-top:40px"> Table showing previous days Menu Records </h4>
    <table border="1" cellpadding="10" cellspacing="0" style="margin: 20px; width: 90%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2; color:#ff0000a3;">
                <th>Sr No.</th>
                <th>Meal Type</th>
                <?php
                if ($messType == "veg") {
                    echo "<th>Veg Menu</th>";
                } elseif ($messType == "nonveg") {
                    echo "<th>Non-Veg Menu</th>";
                } else {
                    echo "<th>Veg Menu</th>";
                    echo "<th>Non-Veg Menu</th>";
                }
                ?>
                <th>Opening Time</th>
                <th>Closing Time</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody style="color:#000000ab">
            <?php
            if ($menuResult && mysqli_num_rows($menuResult) > 0) {
                $count = 1;
                while ($menuRow = mysqli_fetch_assoc($menuResult)) {
                    echo "<tr>";
                    echo "<td>" . $count++ . "</td>";
                    echo "<td>" . ucfirst($menuRow['menutype']) . "</td>";

                    if ($messType == "veg") {
                        echo "<td>" . htmlspecialchars($menuRow['veg_menuitemprice']) . "</td>";
                    } elseif ($messType == "nonveg") {
                        echo "<td>" . htmlspecialchars($menuRow['nonveg_menuitemprice']) . "</td>";
                    } else {
                        echo "<td>" . htmlspecialchars($menuRow['veg_menuitemprice']) . "</td>";
                        echo "<td>" . htmlspecialchars($menuRow['nonveg_menuitemprice']) . "</td>";
                    }

                    echo "<td>" . htmlspecialchars($menuRow['menuotime']) . "</td>";
                    echo "<td>" . htmlspecialchars($menuRow['menuctime']) . "</td>";
                    echo "<td>" . htmlspecialchars($menuRow['menudate']) . "</td>";
                    echo "</tr>";
                }
            } else {
                $colspan = ($messType == 'both') ? 7 : 6;
                echo "<tr><td colspan='$colspan' style='text-align:center;'>No menu history found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>
        </main>



</body>
</html>
