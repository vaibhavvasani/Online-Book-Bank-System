<?php
	session_start();
	require_once('./config.php');
	$correct = 1;
	if(isset($_POST['login_email'])) {
		$email = $_POST['login_email'];
		$pass = $_POST['login_pass'];
		try{
			// echo $email, $pass;
			if($email!="" and $pass!=""){
				print_r($db_connection);
				$query = "SELECT email, name, password, uid from users where email='" . $email ."';";
				$result = pg_query($db_connection, $query);
				if(pg_num_rows($result)>0) {
					$row = pg_fetch_assoc($result);
					$db_pass = $row['password'];
					echo $pass;
					echo $db_pass;
					$correct = strcmp($pass, $db_pass);
					echo $correct;
				}
				else{
					$_SESSION['errorMessage1']="Error: Email not Registered";
					header("Location:./login.php");
					exit();
				}
			}
			else{
				// unset($_SESSION['errorMessage1']);
				$_SESSION['errorMessage1']="Error: Email/Password cannot be empty";
				header("Location:./login.php");
				exit();
			}
			if($correct==0){
				$_SESSION['name']=$row['name'];
				$_SESSION['email']=$row['email'];
				$_SESSION['id']=$row['uid'];
				if(isset($_SESSION['callingPage'])){
					header("Location:./".$_SESSION['callingPage']);
				}
				else {
					header("Location:./index.php");
				}
			}
			else {
				$_SESSION['errorMessage1']="Error: Password Incorrect";
				header("Location:./login.php");
				exit;
			}
		}
		catch(Exception $e){
			$_SESSION['errorMessage1']="Error: Failed!";
			header("Location:./login.php");
			exit;
		}
	}
	else if(isset($_POST['reg_email'])) {
		echo 'here';
		$name = $_POST['reg_name'];
		$email = $_POST['reg_email'];
		$pass = $_POST['reg_pass'];
		$cpass = $_POST['reg_cpass'];
		echo $pass, $cpass;
		if($email!="" and $pass!="" and $name!="" and $cpass!=""){
			if(strcmp($pass, $cpass)==0){
				try{
					print_r($db_connection);
					echo $pass;
					$query3 = "SELECT email from users where email='".$email."';";
					$result3 = pg_query($db_connection, $query3);
					// $row3 = pg_fetch_assoc($result3);
					// $presentEmail = $row3['email'];
					if(pg_num_rows($result3)==0){
						print_r(pg_num_rows($result3));
						$query = "SELECT max(uid) as maxuid from users;";
						$result = pg_query($db_connection, $query);
						$row = pg_fetch_assoc($result);
						$uid = $row['maxuid']+1;
						echo $uid;
						$query1 = "INSERT INTO users(uid, name, email, password) VALUES ($uid, '$name','$email','$pass');";
						$result1 = pg_query($db_connection, $query1);
						if($result1){
							$query2 = "SELECT name, email from users where email='".$email."';";
							$result2 = pg_query($db_connection, $query2);
							$row2 = pg_fetch_assoc($result2);
							$_SESSION['name']=$row2['name'];
							$_SESSION['email']=$row2['email'];
							if(isset($_SESSION['callingPage'])){
								header("Location:./".$_SESSION['callingPage']);
							}
							else{
								header("Location:./index.php");
							}
						}
						else{
							$_SESSION['errorMessage2']="Error: Failed!";
							header("Location:./login.php");
						}
					}
					else{
						$_SESSION['errorMessage2']="Error: Email Already Registered!";
						header("Location:./login.php");
						exit();
					}
				}
				catch(Exception $e){
					$_SESSION['errorMessage2']="Error: Failed!";
					header("Location:./login.php");
					exit;
				}
			}
			else{
				$_SESSION['errorMessage2']="Error: Password and Confirm Password fields do not match";
				header("Location:./login.php");
				exit();
			}
		}
		else{
			$_SESSION['errorMessage2']="Error: Name/Email/Password/Confirm Password cannot be empty!";
			header("Location:./login.php");
			exit();
		}
	}
?>