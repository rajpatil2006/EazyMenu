<?php
include "../config.php"; // Database connection

header('Content-Type: application/json'); // Ensure JSON response



$sql = "SELECT * FROM messdetail";
$result = mysqli_query($con, $sql);

if (!$result) {
    echo json_encode(["error" => mysqli_error($con)]);
    exit;
}

$messes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $messes[] = $row;
}

echo json_encode($messes);
?>
