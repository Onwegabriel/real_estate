<?php
	session_start();
	include("config.php"); 

	if(isset($_POST['update'])){
		//get POST data
		$old = $_POST['old'];
		$new = $_POST['new'];
		$retype = $_POST['retype'];

		//create a session for the data incase error occurs
		$_SESSION['old'] = $old;
		$_SESSION['new'] = $new;
		$_SESSION['retype'] = $retype;

		//connection
		// $con = new mysqli('localhost', 'root', '', 'realestate');

		//get user details
		$sql = "SELECT * FROM admin WHERE aid = '".$_SESSION['auser']."'";
		$query = $con->query($sql);
		$row = $query->fetch_assoc();

		//check if old password is correct
		if(password_verify($old, $row['apass'])){
			//check if new password match retype
			if($new == $retype){
				//hash our password
				$password = password_hash($new, PASSWORD_DEFAULT);

				//update the new password
				$sql = "UPDATE admin SET apass = '$password' WHERE aid = '".$_SESSION['auser']."'";
				if($con->query($sql)){
					$_SESSION['success'] = "Password updated successfully";
					//unset our session since no error occured
					unset($_SESSION['old']);
					unset($_SESSION['new']);
					unset($_SESSION['retype']);
					
					header('location: profile.php?&success');
				}
				else{
					$_SESSION['error'] = $con->error;
				}
			}
			else{
				$_SESSION['error'] = "New and retype password did not match";
			}
		}
		else{
			$_SESSION['error'] = "Incorrect Old Password";
		}
	}
	else{
		$_SESSION['error'] = "Input needed data to update first";
	}

	// header('location: profile.php?&success');
	
?>