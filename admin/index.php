<?php 
ob_start();
	session_start();
	$noNavbar='';
	if (isset($_SESSION['Username'])) {
		header('location: dashboard.php');
	}
	include 'includes/languages/english.php';	// languages
	$pageTitle = lang('LOGIN');
	include "init.php"; // the paths file
	// program login HTTP post REquest
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$username = $_POST['user'];
		$password = $_POST['pass'];
		$hashedPass = sha1($password); // security hashed password

		// check if user exist

		$stmt = $con->prepare(
			"SELECT 
				UserID, Username, Password , FullName , GroupID
			FROM 	
				users 
			WHERE 
				Username = ? 
			AND 
				Password = ?  
			limit 1");
		$stmt->execute(array($username, $hashedPass));
		$users = $stmt->fetch();
		$count = $stmt->rowCount(); 
		// func to know if user exist or no 0 not exist 1 exists
	}
?>	
<!-- admin log in form  -->
<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
	<h4 class="text-center hvr-pulse-grow">Admin Login <i class="fa fa-user hvr-icon-buzz-out"></i></h4>
	<input class="form-control input-lg hvr-wobble-skew" type="text" name="user" placeholder="Username" autocomplete="autocomplete" />
	<input class="form-control input-lg hvr-wobble-skew" type="password" name="pass" placeholder="password" autocomplete="new-password" />
	<input class="btn btn-primary btn-block hvr-buzz-out" type="submit" name="login" value="Login" />
	<?php 
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if ($count > 0 && $users['GroupID'] > 0) {
			$_SESSION['Username']	= $username; 	// Register Session username
			$_SESSION['FullName'] 	= $users['FullName'] ; 	// Register Session full name
			$_SESSION['ID'] 		=	$users['UserID']; 	// Register Session ID
			$_SESSION['GroupID'] 	=	$users['GroupID']; 	// Register Session ID
			header('location: dashboard.php'); 		//Redirect to dashborad paage
			exit(); 								// exit database and script
			}
			elseif ($count > 0 && $users['GroupID'] == 0) {
			$_SESSION['Username'] 	= $username; 	// Register Session username
			$_SESSION['FullName']	= $users['FullName'] ; 	// Register Session full name
			$_SESSION['ID'] 		=	$users['UserID']; 	// Register Session ID
			$_SESSION['GroupID'] 	=	$users['GroupID']; 	// Register Session ID
			header('location: ../index.php'); 		//Redirect to dashborad paage
			exit();
			}
			else 
			{echo "<div class='alert alert-danger text-center'>Wrong password or username</div>";};
		}
	?>
</form>

<?php 
include $tpl.'footer.php'; // footer include
ob_end_flush();
?>
