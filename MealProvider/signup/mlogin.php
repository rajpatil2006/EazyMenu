<?php
	include "../../config.php";
	if(isset($_POST['submit']))
	{
		extract($_POST);
       
		$email=mysqli_real_escape_string($con,$_POST['email']);
		$password=mysqli_real_escape_string($con,$_POST['password']);
        
		$log=mysqli_query($con,"select * from messowner where moemail='$email' and mopassword='$password'") or die(mysqli_error($con));
		if(mysqli_num_rows($log)>0){
			$row=mysqli_fetch_array($log);
            $mId=$row['moid'];
           
		    echo "<script>";
			echo "alert('Login Successful.');";
            echo "window.location.href = '../mdashboard.php?mId=$mId';";
			echo "</script>";
			
		}
		else{
			echo "<script>";
			echo "alert('Login Failed.');";
            echo "window.location.href = 'mlogin.html';";
			echo "</script>";
			
		}
	}

?>