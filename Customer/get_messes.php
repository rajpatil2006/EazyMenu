
<?php
include "../config.php";

$lat = $_GET['lat'];
$lng = $_GET['lng'];

$location = $_GET['location'] ?? '';
$messname = $_GET['messname'] ?? '';
$type = $_GET['type'] ?? '';

$searching = $location || $messname || $type;

if ($searching) {
    // If there are any search filters, we apply them
    $query = "SELECT * FROM messdetail WHERE 1=1";
    if (!empty($location)) {
        $query .= " AND messlocation LIKE '%" . mysqli_real_escape_string($con, $location) . "%'";
    }
    if (!empty($messname)) {
        $query .= " AND messname LIKE '%" . mysqli_real_escape_string($con, $messname) . "%'";
    }
    if (!empty($type)) {
        $query .= " AND messtype LIKE '%" . mysqli_real_escape_string($con, $type) . "%'";
    }
} else {
    // If no search filters, we fetch messes within 1km
    $query = "SELECT *, 
    (6371 * acos(
        cos(radians($lat)) * cos(radians(SUBSTRING_INDEX(messgmap, ',', 1))) *
        cos(radians(SUBSTRING_INDEX(messgmap, ',', -1)) - radians($lng)) +
        sin(radians($lat)) * sin(radians(SUBSTRING_INDEX(messgmap, ',', 1)))
    )) AS distance
  FROM messdetail
  HAVING distance <= 1
  ORDER BY distance ASC";
}

$result = mysqli_query($con, $query);

$messes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $messes[] = $row;
}

header('Content-Type: application/json');
echo json_encode($messes);
?>
