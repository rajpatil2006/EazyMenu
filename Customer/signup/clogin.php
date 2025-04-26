<?php
	include "../../config.php";
	session_start(); 
	if(isset($_POST['submit']))
	{
		extract($_POST);
       
		$email=mysqli_real_escape_string($con,$_POST['email']);
		$password=mysqli_real_escape_string($con,$_POST['password']);
        
		$log=mysqli_query($con,"select * from customer where cemail='$email' and cpassword='$password'") or die(mysqli_error($con));

		if(mysqli_num_rows($log)>0){
			$row=mysqli_fetch_array($log);
            $cId=$row['cid'];
           
			$_SESSION['cId'] = $cId;
		    echo "<script>";
			echo "alert('Login Successful.');";
            echo "window.location.href = '../cdashboard.php';";
			echo "</script>";
			
		}
		else{
			echo "<script>";
			echo "alert('Login Failed.');";
            echo "window.location.href = 'clogin.html';";
			echo "</script>";
			
		}
	}

?>