
<?php

  include "../../config.php";
  if(isset($_POST['submit']))
  {
   
       extract($_POST);
        
  
       $add=mysqli_query($con ,"insert into customer(cfname,clname,cemail,cpassword) values('$fname','$lname','$email','$password') ")or die(mysqli_error($con));
	
	if ($add) {
		echo "<script>;";
		echo "window.alert('Sign Up successfully....!');";
		echo 'window.location.href = "clogin.html";';
		echo "</script>";

	} else {
		echo "<script>;";
		echo "window.alert('Data error....!');";
		echo "</script>";
	}
  }
?>
    
        