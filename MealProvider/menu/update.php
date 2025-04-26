<?php
include "../../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mId = $_POST['mId'];
    $menuId = $_POST['menuId'];
    $mealType = $_POST['mealType']; 
    $menuType = $_POST['menutype'];
    $menuOTime = $_POST['menuotime'];
    $menuCTime = $_POST['menuctime'];
    $menuDate = $_POST['menudate'];
   
    
    // Fetch messid from messowner table
    $messQuery = "SELECT messid FROM messowner WHERE moid = $mId";
    $messResult = mysqli_query($con, $messQuery);
    
    if (!$messResult || mysqli_num_rows($messResult) == 0) {
        die("Mess ID not found.");
    }

    $messData = mysqli_fetch_assoc($messResult);
    $messId = $messData['messid'];

    // Process menu items and prices
    $menuItems = $_POST['menu_item'];
    $menuPrices = $_POST['menu_price'];

    $menuList = [];

    foreach ($menuItems as $index => $item) {
        $menuList[] = $item . "-" . $menuPrices[$index];
    }

    
    
    // Convert to comma-separated string
    $menuString = implode(",", $menuList);
  
    $todayDate = date('Y-m-d');
    $menuQuery = "SELECT * FROM menu WHERE menuid = $menuId";
    $menuResult = mysqli_query($con, $menuQuery);
    $row = mysqli_fetch_assoc($menuResult);
     // Determine which column to update
     
  
     if ($mealType == "nonVeg") {

        $columnName = "nonveg_menuitemprice";
    } else {
        $columnName = "veg_menuitemprice";
    }

    // Update menu details
    $updateQuery = "UPDATE menu SET 
                    menutype = '$menuType', 
                    $columnName = '$menuString', 
                    menuotime = '$menuOTime', 
                    menuctime = '$menuCTime', 
                    menudate = '$menuDate' 
                    WHERE menuid = '$menuId' AND messid = '$messId'";

    if (mysqli_query($con, $updateQuery)) {
        echo "<script>alert('Menu updated successfully!'); window.location.href='todaymenu.php';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
