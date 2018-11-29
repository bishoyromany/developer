<?php
	ob_start();
	session_start();
	if (isset($_SESSION['Username'])) {
		header('location : index.php');
	}
	include 'includes/languages/english.php';	// languages
	$pageTitle = "Sign Up";
	include 'init.php';
	// start sign up program
	if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['signup'])) {
		// get date 
		$Username 		= $_POST['Username'];
		$FullName 		= $_POST['FullName'];
		$Password 		= $_POST['Password'];
		$hashedpass1 	= sha1($_POST['Password1']);
		$hashedpass 	= sha1($_POST['Password']);
		$Email 			= $_POST['Email'];
		// check errors
		$signuperrors = array();
		// start errors check 
		// let's filter all data then
		if (isset($Username)) {
		 $filteruser = filter_var($Username , FILTER_SANITIZE_STRING); // filter username
		}
		if (isset($FullName)) {
		 $filterFull = filter_var($FullName , FILTER_SANITIZE_STRING); // filter full name
		}
		if (isset($Email)) {
		 $filterEmail = filter_var($Email , FILTER_SANITIZE_EMAIL); // filter the email code
		 if (filter_var($filterEmail , FILTER_VALIDATE_EMAIL) != true) {
		 	$signuperrors[] = "Please Write a valid Email address";
		 }
		}
		// username
		if (empty($filteruser)) {
			$signuperrors[] = "You shoudn't leave <strong> Username empty </strong>";
		}
		if (!empty($filteruser) && strlen($filteruser) < 3) {
			$signuperrors[] = "Your Username shoud be <strong> more then 3 characters or numbders </strong>";
		}
		if (checkItem("Username" , "users" , $filteruser) > 0) {
			$signuperrors[] = "<strong> Username Allready Exists </strong>";
		}
		// fullname
		if (empty($filterFull)) {
			$signuperrors[] = "You shoudn't leave <strong> FullName empty </strong>";
		}
		// password
		if (!empty($Password) && strlen($Password) < 6) {
			$signuperrors[] = "Your Password  <strong> Is Week </strong> it should be <stong> at Least 6 character or numbers </strong>";
		}
		if (empty($Password)) {
			$signuperrors[] = "You shoudn't leave <strong> Password empty </strong>";
		}
		if (!empty($Password) && $hashedpass != $hashedpass1) {
			$signuperrors[] = "Your Passwords aren't <strong> The Same </strong>";
		}
		// email
		if (empty($filterEmail)) {
			$signuperrors[] = "You shoudn't leave <strong> Email empty </strong>";
		}
		// if there was no error
		if (empty($signuperrors)) {
			$stmt = $con->prepare("INSERT INTO users ( Username , FullName , Password , Email , Regdate) VALUES (? , ? , ? , ? , NOW() )");
			$stmt->execute(array($filteruser , $filterFull , $hashedpass , $filterEmail));
			$count1 = $stmt->rowCount();
		}
	}



?>
	<div class="caontain_head text-center">
	 <span data-class="login"  class="selected">Login</span> |
	 <span data-class="signup">Sign Up</span>
	</div>
	<div class="container form_contain">
	<div class="form_phone_fix">
		<form class="form-horizontal signup" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" autocomplete="off">
			<div class="row">

				<div class="form-group">
						<!-- start user name -->
					<label title="The name you sign in with" class="control-label col-md-2 col-md-offset-2">Username : </label>
					<div class="col-md-5">
						<input 
						type="text" 
						name="Username" 
						required="required" 
						pattern=".{3,}" 
						title="Username must be more than 3 characters" 
						placeholder="your Username" 
						class="form-control input-lg">
					</div>
				</div>
				<div class="form-group">
						<!-- start user name -->
					<label title="The Name show in your profile but not your sells" class="control-label col-md-2 col-md-offset-2">Full Name : </label>

					<div class="col-md-5">
						<input type="text" name="FullName" required="required" placeholder="Your Full Name" class="form-control input-lg">
					</div>
				</div>
				<div class="form-group">
					<!-- start password -->
				<label title="your password should be strong" class="control-label col-md-2 col-md-offset-2">Password : </label>
				<div class="col-md-5">
					<input type="password" name="Password" required="required" placeholder="Type a strong password" class="form-control input-lg" pattern=".{6,}" title="Password should be more than 6 no/char.">
				</div>
				</div>
				<div class="form-group">
					<!-- start user name -->
				<label title="retype ypur password" class="control-label col-md-2 col-md-offset-2">type Password :</label>
				<div class="col-md-5">
					<input type="password" name="Password1" required="required" placeholder="retype Password again" class="form-control input-lg">
				</div>

				</div>				
				<div class="form-group">
					<!-- start Email -->
				<label title="Email Address must be valid" class="control-label col-md-2 col-md-offset-2">Email Address: </label>
				<div class="col-md-5">
					<input type="email" name="Email" required="required" placeholder="Type your Email address" class="form-control input-lg">
				</div>
				</div>


				<div class="form-group">
					<!-- start submit -->
					<div class="col-md-4 col-md-offset-4">
						<input type="submit"  class="btn btn-success btn-block" value="Sign Up" name="signup">
					</div>
				</div>
			</div>
		</form>
	</div>
	<!-- log in for phones only -->
		<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<h4 class="text-center hvr-pulse-grow"> Login <i class="fa fa-user hvr-icon-buzz-out"></i></h4>
			<input class="form-control input-lg hvr-wobble-skew" type="text" name="user" placeholder="Username" autocomplete="autocomplete" />
			<input class="form-control input-lg hvr-wobble-skew" type="password" name="pass" placeholder="password" autocomplete="new-password" />
			<input class="btn btn-primary btn-block hvr-buzz-out" type="submit" name="login" value="Login" />
		</form>
	<?php // if signed up successfully or not
	if (isset($count1) && $count1 > 0 ){ $alert_success1 = "Signed Up successfull"; redirectAlert(); header("Refresh:100"); } 
	elseif (isset($count1) && $count1 == 0) { $alert_warning1 = "something went wrong retry later"; redirectAlert(); } ?>
	</div>

<?php 
	// echo errors here
	if (isset($signuperrors) && !empty($signuperrors)) {
		foreach ($signuperrors as $error) {
			echo '<div class="alert alert-danger aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$error.'.</div>';
		}
	}

	include $tpl."footer.php"; 
	exit();
 	ob_end_flush(); 

?>