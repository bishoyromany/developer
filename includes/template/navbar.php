<div class="user_navbar">
	<!-- only show if user signed in -->
	<?php 
		if (isset($_SESSION['Username'])) {
	?>
	<div style="padding-top: 50px;"></div>
	<div class="user_nav">
		<div class="container">
			 <!-- Profile links -->
	  <span class="pull-right user_click">
	      
			  <?php 
				// get the image from the users database
				$image = getData("image" , "users" , "WHERE UserID = ?" , $_SESSION['ID'] , "UserID" , "" , "" , "");
				echo '<span class="user_name" id="user_links_menu"><i class="fa fa-user"></i> '.$_SESSION["FullName"].'</span><span style="margin-left:30px;"></span>'.
					'<span class="make_img_absolute"><span class="user_img_contianer">';
					foreach ($image as $img){ 
			  if (empty($img['image'])): 
			  	echo '<img src="avatar-man.jpg" id="user_img_contianer">';
			  elseif (!empty($img['image'])): 
				echo  '<img src="uploads/'.$img['image'].'" id="user_img_contianer">';
			   endif; }
			  echo '
			  	</span> </span>
				<span class="icon_of_toggle"> 
				<i class="fa fa-angle-down down"></i>
				</span>';
			  ?>
		      <div class="user_links">
		      	<?php
		      		if (isset($_SESSION['Username']) && isset($_SESSION['Admin']) && proveAdmin() > 0) { 
		      		echo '<a href="admin/dashboard.php"><i class="bom"></i> Dashboard <i class="fa fa-clipboard "></i></a>';
		      		}
		      	?>
		      		<a href="profile.php"><i class="bom"></i>My Profile <i class="fa fa-user-circle fa-fw"></i></a>
		      		<a href="profile.php?do=Add"><i class="bom"></i>New Item <i class="fa fa-plus fa-fw"></i></a>
		      		<a href="profile.php#my-items"><i class="bom"></i> My Items <i class="fa fa-tags fa-fw"></i></a>
		      		<a href="profile.php#my-comments"><i class="bom"></i> My Comments <i class="fa fa-comments fa-fw"></i></a>
		      		<a href="profile.php?do=Edit"><i class="bom"></i> Edit <i class="fa fa-edit fa-fw"></i></a>
		      		<a href="#"><i class="bom"></i> Settings <i class="fa fa-cog fa-fw"></i></a>
		      		<a href="logout.php"><i class="bom"></i> Logout <i class="fa fa-sign-out fa-fw"></i></a>

		      </div>
	      </span>
	      <a class="navbar-brand hvr-grow-shadow hidden-xs logo" href="index.php"><?php echo lang('HOME'); ?></a>
		</div>
	</div>
	<?php 
// end profile links show if signed in only 
	 } ?>	


<div class="main_navbar">
	<nav class="navbar navbar-inverse">
	  <div class="container">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <?php if (!isset($_SESSION['Username'])) {?>
	      <span class="visible-xs sign_upin_phone"><a class="btn btn-primary btn-sm" href="signup.php">Login</a> <a class="btn btn-success btn-sm" href="signup.php">SignUp</a> </span>

	    <?php
				}
	      	if (isset($_SESSION['Username']))
	      	{
	    ?>
	     	<a class="navbar-brand visible-xs hvr-grow-shadow" href="index.php"><?php echo lang('HOME'); ?></a>
	    <?php	
	      	}
	       if (!isset($_SESSION['Username'])) { ?>
	      <a class="navbar-brand hvr-grow-shadow" href="index.php"><?php echo lang('HOME'); ?></a>
	  	<?php } ?>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="app-nav">
	      <ul class="nav navbar-nav active_categories">
  		    <?php
		      		// if user didn't sign in do all of this
  			if (!isset($_SESSION['Username'])) {
	  			echo 
	  			'<li>
	  				<span class="login_signup btn btn-success">
	  				<span class="login_start" id="login_start"> Login </span>  ';
	  			?>
	      		<form class="login_form hidden-xs" id="login_form" method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>">
	      			<span></span>
	      			<input id="login_form1" class="form-control" type="text" name="user" placeholder="Username">
	      			<input id="login_form2" class="form-control" type="password" name="pass" placeholder="Password">
	      			<input id="login_form3" name='login' class="btn btn-primary btn-block" type="submit" value="Login">
	      			</form>
	      			<a id="signup" href="signup.php" class="btn btn-success btn-sm"> SignUp </a>
	      			</span>
	  				</li>
  			<?php } ?>
			  <?php
			  	$parents = getData("*" , "categories" , "WHERE parent = ?" , 0 , "Ordring" , "ASC" , "" , ""); 
	      		foreach ($parents as $cat) {
	      	?>
				  <li class="li_underline">
					  <?php $subcats = getData("*" , "categories" , "WHERE parent = ?" , $cat['ID'] , "ID" , "" , ""); 
					  if (empty($subcats))
					  { ?>
					  <a href="category.php?catid=<?php echo $cat['ID'] ?>&catname=<?php echo str_replace(" ","-",$cat['Name']) ?> ">
						<?php  echo $cat['Name'];?> </a>
						<?php 
					  }elseif (!empty($subcats)){ ?> 
						<a style="cursor:pointer;"><?php echo $cat['Name']; ?> <i class='fa fa-angle-double-down'></i></a>
						<ul class="subcats_navbar list-unstyled"><li class="mister_li_sub">
							<a href="category.php?catid=<?php echo $cat['ID'] ?>&catname=<?php echo str_replace(" ","-",$cat['Name']) ?> ">All <?php echo $cat['Name']; ?></a>
						<?php
							foreach ($subcats as $c){ ?>
					  			<a href="category.php?catid=<?php echo $c['ID'] ?>&catname=<?php echo str_replace(" ","-",$c['Name']) ?>"> <?php echo $c['Name'] ?> </a>
						  <?php } echo '<li></ul>'; } ?>
				  </li>
	      	<?php
	      		}
			?>
	      </ul>
	    </div>
	  </div>
	</nav>
</div>
</div>
<?php
	// program login HTTP post REquest
	if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login']))
	{
		$username = $_POST['user'];
		$password = $_POST['pass'];
		$hashedPass = sha1($password); // security hashed password

		// check if user exist

		$stmt = $con->prepare(
			"SELECT 
				UserID, Username, Password , FullName , GroupID , RegStatus
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
		if ($count > 0 && $users['GroupID'] > 0) {
		$_SESSION['Username']	=	$username; 	// Register Session username
		$_SESSION['FullName'] 	= 	$users['FullName'] ; 	// Register Session full name
		$_SESSION['ID'] 		=	$users['UserID']; 	// Register Session ID
		$_SESSION['GroupID'] 	=	$users['GroupID']; 	// Register Session ID of admin
		$_SESSION['Admin'] 		=	$users['GroupID']; 	// Register Session admin
		header('location: admin/dashboard.php'); 		//Redirect to dashborad paage
		exit(); 								// exit database and script
		}
		elseif ($count > 0 && $users['GroupID'] == 0 && $users['RegStatus'] > 0) {
		$_SESSION['Username'] 	= 	$username; 	// Register Session username
		$_SESSION['FullName']	= 	$users['FullName'] ; 	// Register Session full name
		$_SESSION['ID'] 		=	$users['UserID']; 	// Register Session ID
		$_SESSION['GroupID'] 	=	$users['GroupID']; 	// Register Session ID
		header('location: index.php'); 		//Redirect to samepage paage
		exit();
		}elseif ($count > 0 && $users['RegStatus'] < 1 ) {
			echo '<div class="alert alert-danger aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>Please active your Account to sign in</div>';	
		}
		else 
		{
			echo "<div class='alert alert-danger text-center'>Wrong password or username</div>";
		}
	}
?>