<?php
include "../config.php";
session_start();
if (!isset($_SESSION['cId'])) {
    // Redirect to login if session is not set
    header("Location: signup/clogin.html");
    exit();
}
$cId = $_SESSION['cId'];             

// Fetch customer details
$customerQuery = "SELECT * FROM customer WHERE cid = $cId";
$customerResult = mysqli_query($con, $customerQuery);
$customer = mysqli_fetch_assoc($customerResult);
?>
<script>
    const cId = <?php echo json_encode($cId); ?>;
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EazyMenu - Find Nearby Meals</title>
    <link rel="stylesheet" href="cdashboard.css">
</head>
<body>

    <!-- Header -->
    <header>
        <div class="logo">EazyMenu</div>
        <nav>
            <ul>
                <li><a href="profile.php" class="profile-btn">Profile</a></li>
                <li><a href="logout.php" class="login-btn">Log Out</a></li>
            </ul>
        </nav>
    </header>

    <h2 style="margin-left:50px; color: #d3416a;;"> Welcome <b style="color: #696969;"><?php echo $customer['cfname']." ".$customer['clname'] ?> !!</b>  </h2>

    <!-- Search Filters -->
    <div class="search-container">
        <input type="text" id="searchLocation" placeholder="Search by Location">
        <input type="text" id="searchMessName" placeholder="Search by Mess Name">
        <input type="text" id="searchType" placeholder="Veg / Non-Veg">
    </div>

    <!-- Content Section -->
    <section class="content">
        <h1>Nearby Messes</h1>
        <div id="messContainer" class="mess-grid">
            <!-- Mess cards will be dynamically inserted here -->
        </div>
    </section>

    <script src="script.js"></script>
    <script>
document.addEventListener("DOMContentLoaded", () => {
    let userLat = null;
    let userLng = null;

    navigator.geolocation.getCurrentPosition(
        pos => {
            userLat = pos.coords.latitude;
            userLng = pos.coords.longitude;
            fetchAndDisplayMesses();
        },
        () => alert("Could not get location.")
    );

    const searchInputs = ["searchLocation", "searchMessName", "searchType"];
    searchInputs.forEach(id => {
        document.getElementById(id).addEventListener("input", fetchAndDisplayMesses);
    });

    function fetchAndDisplayMesses() {
        const location = document.getElementById("searchLocation").value.trim();
        const messname = document.getElementById("searchMessName").value.trim();
        const type = document.getElementById("searchType").value.trim();

        if (!userLat || !userLng) return;

        let url = `get_messes.php?lat=${userLat}&lng=${userLng}`;

        if (location || messname || type) {
            if (location) url += `&location=${encodeURIComponent(location)}`;
            if (messname) url += `&messname=${encodeURIComponent(messname)}`;
            if (type) url += `&type=${encodeURIComponent(type)}`;
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {
                displayMesses(data, userLat, userLng);
            });
    }

    function getDistance(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }
});
</script>

</body>
</html>
