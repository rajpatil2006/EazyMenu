<?php
include "../../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mId = $_POST['mId'];
    $mealType = $_POST['mealType']; // veg, nonveg, or both
    $menuType = $_POST['menutype']; // breakfast, lunch, dinner
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

    // Check if a row already exists for the same messid, menutype and menudate
    $checkQuery = "SELECT * FROM menu WHERE messid = '$messId' AND menutype = '$menuType' AND menudate = '$menuDate'";
    $checkResult = mysqli_query($con, $checkQuery);

    if ($checkResult && mysqli_num_rows($checkResult) > 0) {
        // Row exists — update the appropriate column
        $existingRow = mysqli_fetch_assoc($checkResult);
        
        if ($mealType == 'veg') {
            $updateQuery = "UPDATE menu SET veg_menuitemprice = '$menuString', menuotime = '$menuOTime', menuctime = '$menuCTime' 
                            WHERE menuid = " . $existingRow['menuid'];
        } elseif ($mealType == 'nonveg') {
            $updateQuery = "UPDATE menu SET nonveg_menuitemprice = '$menuString', menuotime = '$menuOTime', menuctime = '$menuCTime' 
                            WHERE menuid = " . $existingRow['menuid'];
        } else {
            // both (if applicable in future)
            $updateQuery = "UPDATE menu SET veg_menuitemprice = '$menuString', nonveg_menuitemprice = '$menuString',
                            menuotime = '$menuOTime', menuctime = '$menuCTime' WHERE id = " . $existingRow['id'];
        }

        if (mysqli_query($con, $updateQuery)) {
            echo "<script>alert('Menu updated successfully!'); window.location.href='todaymenu.php';</script>";
        } else {
            echo "Error updating menu: " . mysqli_error($con);
        }
    } else {
        // Row does not exist — insert new row
        $vegMenu = ($mealType == 'veg') ? $menuString : "";
        $nonVegMenu = ($mealType == 'nonveg') ? $menuString : "";

        $insertQuery = "INSERT INTO menu (messid, menutype, veg_menuitemprice, nonveg_menuitemprice, menuotime, menuctime, menudate) 
                        VALUES ('$messId', '$menuType', '$vegMenu', '$nonVegMenu', '$menuOTime', '$menuCTime', '$menuDate')";

        if (mysqli_query($con, $insertQuery)) {
            echo "<script>alert('Menu added successfully!'); window.location.href='todaymenu.php';</script>";
        } else {
            echo "Error inserting menu: " . mysqli_error($con);
        }
    }
}
?>
