<?php
include "../../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mId = $_POST['mId'];
    $mealType = $_POST['mealType']; // veg or nonveg
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

    // Determine the column to insert into
    $columnName = ($mealType == 'veg') ? "veg_menuitemprice" : "nonveg_menuitemprice";

    // Insert into database
    $insertQuery = "INSERT INTO menu (messid, menutype, $columnName, menuotime, menuctime, menudate) 
                    VALUES ('$messId', '$menuType', '$menuString', '$menuOTime', '$menuCTime', '$menuDate')";

    if (mysqli_query($con, $insertQuery)) {
        echo "<script>alert('Menu added successfully!'); window.location.href='todaymenu.php?mId=$mId';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
