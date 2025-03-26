<?php
include "../config.php";
$cId = $_GET['cId'];               

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
                <li><a href="profile.php?cId=<?php echo $cId;?>" class="profile-btn">Profile</a></li>
                <li><a href="../index.html" class="login-btn">Log Out</a></li>
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
</body>
</html>
