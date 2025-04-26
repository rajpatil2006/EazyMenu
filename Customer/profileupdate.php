<?php
include "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cid = $_POST['cid'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Update customer details
    $updateQuery = "UPDATE customer SET cfname = '$fname', clname = '$lname', cemail = '$email', cpassword = '$password' WHERE cid = $cid";
    
    if (mysqli_query($con, $updateQuery)) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile!'); window.history.back();</script>";
    }
}
?>
