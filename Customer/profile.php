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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EazyMenu - Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>

    <!-- Header -->
    <header>
        <div class="logo">EazyMenu</div>
        <nav>
            <ul>
                <li><a href="cdashboard.php" class="profile-btn">Back to Dashboard</a></li>
                <li><a href="logout.php" class="login-btn">Log Out</a></li>
            </ul>
        </nav>
    </header>

    <!-- Profile Form -->
    <section class="content">
        <div class="signup-box">
            <h2>Profile</h2>
            <form action="profileupdate.php" method="post">
                <input type="hidden" name="cid" value="<?php echo $customer['cid']; ?>"> 
                <input type="text" name="fname" placeholder="First Name" value="<?php echo $customer['cfname']; ?>" required>
                <input type="text" name="lname" placeholder="Last Name" value="<?php echo $customer['clname']; ?>" required>
                <input type="email" name="email" placeholder="Email Address" value="<?php echo $customer['cemail']; ?>" required>
                <input type="password" name="password" placeholder="Password" value="<?php echo $customer['cpassword']; ?>" required>
                <button type="submit" name="submit" class="btn-signup">Update</button>
            </form>
        </div>
    </section>

</body>
</html>
