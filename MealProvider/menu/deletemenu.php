<?php
include "../../config.php";


    $menuid = intval($_GET['menuid']);
    $mId = intval($_GET['mId']);
    $mtype = $_GET['mtype']; // 'veg' or 'nonveg'

    echo "Hello";
    // Determine which column to update based on mtype
    if ($mtype == 'veg') {
     
        $updateQuery = "UPDATE menu SET veg_menuitemprice = NULL WHERE menuid = $menuid";
    } elseif ($mtype == 'nonveg') {
        $updateQuery = "UPDATE menu SET nonveg_menuitemprice = Null WHERE menuid = $menuid";
    } else {
        // Invalid mtype; exit or handle error
        die("Invalid menu type.");
    }

    // Update the menu row based on the mtype
    if (mysqli_query($con, $updateQuery)) {
        // Check if both columns are now empty (veg_menuitemprice and nonveg_menuitemprice)
        $checkQuery = "SELECT veg_menuitemprice, nonveg_menuitemprice FROM menu WHERE menuid = $menuid";
        $checkResult = mysqli_query($con, $checkQuery);

        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            $row = mysqli_fetch_assoc($checkResult);

            // If both columns are empty, delete the row
            if (empty($row['veg_menuitemprice']) && empty($row['nonveg_menuitemprice'])) {
                $deleteQuery = "DELETE FROM menu WHERE menuid = $menuid";
                if (mysqli_query($con, $deleteQuery)) {
                    echo "
                    <script>
                        alert('Menu deleted successfully!');
                        window.location.href = 'todaymenu.php';
                    </script>
                    ";
                } else {
                    echo "
                    <script>
                        alert('Failed to delete menu.');
                        window.location.href = 'todaymenu.php';
                    </script>
                    ";
                }
            } else {
                // If not both are empty, redirect back with a success message
                echo "
                <script>
                    alert('Menu Deleted successfully!');
                    window.location.href = 'todaymenu.php';
                </script>
                ";
            }
        } else {
            echo "
            <script>
                alert('Error checking menu.');
                window.location.href = 'todaymenu.php';
            </script>
            ";
        }
    } else {
        echo "
        <script>
            alert('Failed to update menu.');
            window.location.href = 'todaymenu.php';
        </script>
        ";
    }

?>
