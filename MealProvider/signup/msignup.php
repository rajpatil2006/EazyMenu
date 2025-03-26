
<?php

  include "../../config.php";
  if(isset($_POST['btn-signup']))
  {
   
       extract($_POST);
  
       $add=mysqli_query($con ,"insert into messowner(mofname,molname,moemail,mopassword) values('$fname','$lname','$email','$password') ")or die(mysqli_error($con));
	
	if ($add) {
		echo "<script>;";
		echo "window.alert('Sign Up successfully....!');";
		echo 'window.location.href = "mlogin.html";';
		echo "</script>";

	} else {
		echo "<script>;";
		echo "window.alert('Data error....!');";
		echo "</script>";
	}
  }
?>