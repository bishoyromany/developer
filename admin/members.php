<?php
	ob_start();
	/*
	((((((((((((((((((Member page))))))))))))))))))))))
	**** Manage Members
	**** EDIT members
	***************************************************	
	*/
	session_start();
	if (isset($_SESSION['Username'])){
	include 'includes/languages/english.php';	// languages 
	$pageTitle =lang('MEMBERS') ; //title
	include 'init.php';	// the inip page
	}
 	else {header('location: index.php');	// if the method wasn't post
 	};
 // check if admin or not
if (proveAdmin() == 1)
{
	// the do if condition
	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';




	// start manage
	if ($do == 'Manage') {
		// manage page
		$query = ''; // check the pending and active members
		if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
			$query = 'AND RegStatus = 0';
		}
		// select all user expect admin
		$stmt= $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
		// just execute
		$stmt->execute();
		// featch all data for rows
		$rows = $stmt->fetchAll();
		if (! empty($rows)) {

		?>
		<h1 class="text-center"><?php echo lang('MANAGEMEMBERS') ?></h1>
		<div class="container">
			<div class="table-responsive">
				<table class="main-table text-center table table-bordered">
					<tr>
						<td><?php echo lang('ID') ?></td>
						<td>Image</td>
						<td><?php echo lang('USERNAME') ?></td>
						<td><?php echo lang('EMAIL') ?></td>
						<td><?php echo lang('FULLNAME') ?></td>
						<td><?php echo lang('REGDATE') ?></td>
						<td><?php echo lang('CONTROL') ?></td>
					</tr>
					<?php
					// featch data 
						foreach ($rows as $row) {
							echo '<tr>';
							echo '<td>'.$row['UserID'].'</td>';
							if (!empty($row['image'])):
							echo '<td>'.'<image style="max-width:70px;max-height:70px;border-radius:50%;padding:0px;margin:0px;" src="../uploads/'.$row['image'].'"></td>';
							else:
							echo '<td><image style="max-width:70px;max-height:70px;border-radius:50%;padding:0px;margin:0px;" src="../uploads/avatar.jpg"></td>';
							endif;
							echo '<td>'.$row['Username'].'</td>';
							echo '<td>'.$row['Email'].'</td>';
							echo '<td>'.$row['FullName'].'</td>';
							echo '<td>'.$row['Regdate'].'</td>';
							echo '<td>';
							echo '<a href="members.php?do=Edit&UserID='.$row['UserID'].'" class="btn btn-success hvr-icon-pulse-grow"><i class="fa fa-edit hvr-icon"></i> '.lang('EDIT').'</a> ';
							echo '<a href="members.php?do=Delete&UserID='.$row['UserID'].'" class="btn btn-danger confirm hvr-icon-pulse-grow"><i class="fa fa-close hvr-icon"></i> '.lang('DELETE').'</a>';
							if ($row['RegStatus'] == 0) {
								echo '	<a href="members.php?do=Pending&UserID='.$row['UserID'].'" class="btn btn-info confirm hvr-icon-pulse-grow"><i class="fa fa-check hvr-icon"></i> '.lang('ACTIVATE').'</a>';
							}elseif ($row['RegStatus'] == 1) {
								echo '	<a href="members.php?do=Deactive&UserID='.$row['UserID'].'" class="btn btn-primary confirm hvr-icon-pulse-grow"><i class="fa fa-close hvr-icon"></i> '.lang('DEACTIVATE').'</a>';
							}
							echo '</td>';
							echo '</tr>';
						}
					?>
				</table>
			</div>
			<a href="?do=Add" class="btn btn-primary"> <i class="fa fa-plus"></i> <?php echo lang('NEWMEMBER') ?> </a>
		</div>
		<?php
		}else
		{
		echo '<h1 class="text-center">Manage Members empty :(</h1>
			<div class="container">
				<div class="nice_message">No Members To Show</div>
				<a class="btn btn-primary" href="?do=Add">Add New Member</a>
			</div>';

		}
	}
// end Manage

	// start Add member form
	elseif ($do == 'Add'){

		?>
		<div class="container">
			<h1 class="text-center"><?php echo lang('ADDNEWMEMBER') ?></h1>
			<?php 
				$alert_warning1 = "All Fields with red star are Required";
				$alert_info1 = 'hover on the eye <i class="fa fa-eye"></i> to see password';
				redirectAlert();
			 ?>
			<form class="form-horizontal" method="POST" action="?do=Insert" enctype='multipart/form-data'>
				<div class="row">
					<div class="form-group">
						<!-- Input username -->
						<label class="control-label col-md-2"><?php echo lang('USERNAME') ?> : </label>
						<div class="col-md-4">
							<input type="text" name="username" class="form-control" required="required" placeholder="Write Your Username...">
						</div>
						<!-- Input password -->
						<label class="control-label col-md-2"><?php echo lang('PASSWORD') ?> : </label>
						<div class="col-md-4">
							<input type="password" name="password" class="password form-control" required="required" placeholder="Write Your Password..." />
							<i class="show-pass hvr-icon-pulse-grow"> <i class="show-pass fa fa-eye fa-1x hvr-icon"></i> </i>
						</div>
					</div>
					<div class="form-group">
						<!-- Input Emal -->
						<label class="control-label col-md-2"><?php echo lang('EMAIL') ?> : </label>
						<div class="col-md-4">
							<input type="email" name="email" class="form-control" required="required" placeholder="Write your Email Address...">
						</div>
						<!-- Input Full Name -->
						<label class="control-label col-md-2"><?php echo lang('FULLNAME') ?> : </label>
						<div class="col-md-4">
							<input type="text" name="full" class="form-control" required="required" placeholder="Write Your Real Name...">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">User Image : </label>
						<div class="col-md-4 user_image_container">
							<div class="user_image_background">
							<input type="file" name="image" class="user_image form-control">
							<span class="user_image_alert">Max <i class="fa fa-image fa-fw"></i> Size 2MP</span>
							<span class="btn btn-default">Upload Image<i class="fa fa-upload fa-fw"></i></span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<!-- Input submit -->
						<div class="col-md-4 col-md-push-4">
							<input type="submit" value="<?php echo lang('ADD') ?>" class="btn btn-primary btn-block">
						</div>
					</div>
				</div>
			</form>
		</div>


		<?php	
	}
// End Add 






	// start Insert
	elseif ($do == 'Insert') { 

		echo "<div class='container'>";
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$user 			= $_POST['username'];
			$pass 			= $_POST['password'];
			$hashedpass 	= sha1($_POST['password']);
			$email 			= $_POST['email'];
			$name 			= $_POST['full'];
			$imagename 		= $_FILES['image']['name'];
			$imagetmpname 	= $_FILES['image']['tmp_name'];
			$imagesize 		= $_FILES['image']['size'];
		   @$imagetype 		= strtolower ( end( explode( "." , $imagename ) ) );
			// upload image type
			$images_type = array("png" , "jpg" , "jpeg");
			// errors function
			$formErrors = array();
			// username
			if (empty($user)) {
				$formErrors[] = "you souldn't leave username <strong> empty </strong>";
			}
			if (strlen($user) < 4 ) {
				$formErrors[] = "your username shouldn't be  <strong> less than 4 characters or numbers </strong>";
			}
			// password
			if (empty($pass)) {
				$formErrors[] = "you souldn't leave password <strong> empty </strong>";
			}
			if (strlen($pass) < 6 ) {
				$formErrors[] = "your password shouldn't be  <strong> less than 6 characters or numbers </strong>";
			}
			// password
			if (empty($name)) {
				$formErrors[] = "you souldn't leave Full Name <strong> empty </strong>";
			}
			if (strlen($name) < 4 ) {
				$formErrors[] = "your Full Name shouldn't be  <strong> less than 4 characters</strong>";
			}
			if (!empty($imagename) && ! in_array($imagetype , $images_type))
			{
				$formErrors[] = "Please Insert an <strong>image</strong>";
			}
			if ($imagesize > 2097152)
			{
				$formErrors[] = "Max Photo Size is <strong>2MP</strong>";
			}
			// chicking errors
			if (!empty($formErrors))
			{
				// foreach errors if exixt
				foreach ($formErrors as $error) {
				echo "<div class='alert alert-danger text-center'>" . $error . '</div>' ;

			}
				// redirect
				$alert_danger1 = "please Read The Errors Above And try To fix Them";
				$alert_redirect_back = "active";
				$seconds = 5 ;
				redirectAlert();
				
			}
	
			// if there's no errors insert data
			if (empty($formErrors)) {
				// check if user name exist or no in the database
				$value = $user; // got it from above user = post['username']
				$check = checkItem('Username', 'users' , $value);
				// if username exists do this
				if ($check == 1) {
					$alert_danger1 = "UserName " . $_POST['username'] . " is already exist please choose another one";
					$alert_redirect_back = "active";
					$seconds = 5 ;
					redirectAlert();
				}elseif($check == 0)
				{
				// prepare the image name and data
				$image = rand(1 , 10000000) . $imagename;
				move_uploaded_file($imagetmpname , "../uploads//".$image);
				// insert data
				$stmt = $con->prepare("
					INSERT INTO 
						users 
							(Username, Password, Email, FullName, RegStatus ,Regdate , image ) 
					VALUES 
							(:user ,:pass ,:email ,:name , 1, now() , :image)");
				$stmt->execute(array(
					':user' 	=> $user 		,	
					':pass' 	=> $hashedpass 	,	
					':email' 	=> $email 		,
					':name'	 	=> $name		,
					':image'	=> $image
				));
				$stmt->rowCount();
		?>
		<h1 class="text-center">Insert New Member</h1>
		<?php
		$alert_success1 = $stmt->rowCount()." Member added successfully";
		$alert_redirect_custom = "?do=Manage";
		$place = "?do=Manage";
		$seconds = 0.5 ;
		redirectAlert();
			}
		} 
		}else
		{	
			$alert_danger1 = "You Can't Browse This Page Directly";
			$alert_redirect_home = "active";
			redirectAlert();
		}
		echo "</div>";
		}
		// end Insert



		// start edit
	
	elseif ($do == 'Edit') 
	{

		// checking the get request id
		$UserID =isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
		// getting data from data base
		$stmt=$con->prepare("SELECT * FROM users WHERE UserID = ?");
		$stmt->execute(array($UserID));
		$row = $stmt->fetch();
		$count = $stmt->rowCount(); 
		// check if user exists
		if ($count > 0)
		{
		// create the form
?>
			<h1 class="text-center"><?php echo lang('EDITMEMBER') ?></h1>
			<div class="container">
				<?php
					$alert_info1 = "All Fields With <strong style='color:red;'> Red </strong> Star Are Required";
					redirectAlert();
				?>
				<form class="form-horizontal" action="?do=Update" method = "post" enctype='multipart/form-data'>
					<div class="row">
					<!-- user name -->
					<div class="form-group form-group-lg">
						<label class="col-md-2 control-label"><?php echo lang('USERNAME') ?> : </label>
						<div class="col-md-4">
							<input type="text" name="Username" class="form-control" required="required"autocomplete="off" value="<?php echo $row['Username'] ?>" />
							<input type="hidden" name="UserID" value="<?php echo $UserID ?>"><!-- Hidden userid -->
						</div>
						<!-- Password name -->
						<label class="col-md-2 control-label"><?php echo lang('PASSWORD') ?> : </label>
						<div class="col-md-4">
							<input type="password" name="newPassword" placeholder="Click here to Change password" class="form-control" autocomplete="new-password">
							<input type="hidden" name="oldPassword" class="form-control" autocomplete="new-password" value="<?php echo $row['Password'] ?>" /> <!-- Hidden password -->
						</div>
					</div>
					<!-- email name -->
					<div class="form-group form-group-lg">
						<label class="col-md-2 control-label"><?php echo lang('EMAIL') ?> : </label>
						<div class="col-md-4">
							<input type="email" name="email" class="form-control" required="required" value="<?php echo $row['Email'] ?>" />
						</div>
					<!-- FullName name -->
						<label class="col-md-2 control-label"><?php echo lang('FULLNAME') ?> : </label>
						<div class="col-md-4">
							<input type="text" name="full" class="form-control" required="required" value="<?php echo $row['FullName'] ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Edit Image : </label>
						<div class="col-md-4 user_image_container">
							<div class="user_image_background">
							<input type="file" name="image" class="user_image form-control">
							<span class="user_image_alert">Max <i class="fa fa-image fa-fw"></i> Size 2MP</span>
							<span class="btn btn-default">Change Image<i class="fa fa-upload fa-fw"></i></span>
							</div>
						</div>
					</div>
					<!-- Submit -->
					<div class="form-group">
						<div class="col-md-4 col-md-push-4">
						<input type="submit" value="<?php echo lang('SAVE') ?>" class="btn btn-primary btn-block">
						</div>
					</div>
				</div>
				</form>
			</div>

<?php
		}else // if user not exists
		{
			$alert_danger1 = "There is No such member";
			$alert_redirect_home = 'home';
			redirectAlert();
		}
	}
	// end edit 




	//stat update
	elseif ($do == 'Update')
	{

?>
	<h1 class="text-center">Update Member</h1>
<?php
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			echo "<div class='container text-center'>";

			$id 			= $_POST['UserID'];
			$user 			= $_POST['Username'];
			$email	 		= $_POST['email'];
			$name 			= $_POST['full'];
			$imagename 		= $_FILES['image']['name'];
			$imagetmpname 	= $_FILES['image']['tmp_name'];
			$imagesize 		= $_FILES['image']['size'];
			// get image type
		   @$image_type 	= strtolower ( end( explode( "." , $imagename ) ) );
			//password trick
			$pass = empty($_POST['newPassword']) ? $_POST['oldPassword'] : sha1($_POST['newPassword']);
			// validate the form
			// the allowed images types
			$image_allow = array("png" , "jpeg" , "jpg");
			// collect errors
			$formErrors = array();
			//username
			if (strlen($user) < 4)
			{
				$formErrors[] = "Username Can't be <strong> Less Than 3 </strong> Characters";
			}
			//username
			if (strlen($user) > 20)
			{
				$formErrors[] = "Username Can't be <strong> more Than 20 </strong> Characters";
			}
			//username
			if (empty($user))
			{
				$formErrors[] = "Username Can't be <strong> Empty </strong>";
			}

			// password
			if (strlen($_POST['newPassword']) < 6 and strlen($_POST['oldPassword']) < 6)
			{
				$formErrors[] = "Password Can't be Less Than <strong> 6 numbers or Characters </strong>";
			}
			//full name
			if (empty($name)) {
				$formErrors[] = "Your full name shouldn't be <strong> empty </strong>";
			}
			//full name
			if (strlen($name) < 6) {
				$formErrors[] = "Your full name shouldn't be Less than <strong> 6 Characters </strong>";
			}
			// email
			if (empty($email)) {
				$formErrors[] = "Yoor Email Can't be <strong> empty </strong>";
			}
			// image 
			if (!empty($imagename) && ! in_array($image_type , $image_allow))
			{
				$formErrors[] = "Please Insert an <strong>image</strong>";
			}
			if ($imagesize > 2097152)
			{
				$formErrors[] = "Max Photo Size is <strong>2MP</strong>";
			}
			// loop and echo the errors
			foreach ($formErrors as $error) {
				echo '<div class="alert alert-danger">'	.$error . '</div>';
			}
			if (!empty($formErrors)) {
				$alert_danger1 = "Place read the errors above";
				$alert_redirect_back = "back";
				$seconds = 5;
				redirectAlert();
			}
			// check if there's errors if not resume the update or insert
			if (empty($formErrors))
			{
				// username if exist
				$username = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
				$username->execute(array($user , $id));
				$countname = $username->rowCount();
				if ($countname > 0) {
					$alert_warning1 = "Usernaem Allready Exists";
					$alert_redirect_back = "active";
					redirectAlert();
				}else
				{
					// change image name
					$image = RAND(1,100000000) . $imagename ;
					move_uploaded_file($imagetmpname , "../uploads//" .$image );
					// update the data
					$stmt = $con->prepare("UPDATE users SET Username = ? , Email = ? , FullName = ? , Password = ? , image = ? WHERE UserID = ?");
					$stmt-> execute(array($user, $email, $name, $pass, $image , $id ));
					// success or not
					$alert_success1 =  "Your infromation had been Updated successfully " .$stmt->rowCount(). " Rows updated";
					$alert_redirect_back = 'active';
					$seconds = 3;
					redirectAlert();
				}
			}
		}else
		{
			$alert_danger1 = "You can't Browse This page Directly";
			$alert_redirect_home = "active";
			redirectAlert();
		}
			echo "</div>";

	}	
// End Update







// Delete member page
	elseif ($do == 'Delete') 
	{

		// checking the get request id
		$UserID =isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
		// getting data from data base depend this id
		$check = checkItem('UserID' , 'users' , $UserID);
		// check if user exists
		if ($check > 0) {
			$stmt=$con->prepare("DELETE FROM users WHERE UserID = :userid");
			$stmt->bindParam(':userid', $UserID);
			$stmt->execute();
			$alert_success1 =  'Only '.$stmt->rowCount().' Member Deleted successfully ';
			$alert_redirect_back = "active";
			$seconds = 0.5;
			redirectAlert();

		}else
			{
				$alert_redirect_back = "active";
				redirectAlert();
			}
	}
// End Delete Members



//End Active Pending
	elseif ($do == 'Pending') 
	{

		// checking the get request id
		$UserID =isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
		// getting data from data base depend this id
		$check = checkItem('UserID' , 'users' , $UserID);
		// check if user exists
		if ($check > 0) {
			$stmt=$con->prepare("UPDATE users SET RegStatus = :no WHERE UserID = :id");
			$stmt->execute(array(
				':no' => 1 ,
				':id' => $UserID
			));
			$alert_success1 =  'Only '.$stmt->rowCount().' Member Activated successfully ';
			$alert_redirect_back = "active";
			$seconds = 0.5;
			redirectAlert();

		}else
			{
				$alert_redirect_back = "active";
				redirectAlert();
			}
	}

//Start Active Pending



//End Active Pending
	elseif ($do == 'Deactive') 
	{

		// checking the get request id
		$UserID =isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
		// getting data from data base depend this id
		$check = checkItem('UserID' , 'users' , $UserID);
		// check if user exists
		if ($check > 0) {
			$stmt=$con->prepare("UPDATE users SET RegStatus = :no WHERE UserID = :id");
			$stmt->execute(array(
				':no' => 0 ,
				':id' => $UserID
			));
			$alert_success1 =  'Only '.$stmt->rowCount().' Member Deactive successfully ';
			$alert_redirect_back = "active";
			$seconds = 0.5;
			redirectAlert();

		}else
			{
				$alert_redirect_back = "active";
				redirectAlert();
			}
	}

//Start Active Pending







	include $tpl.'footer.php';	// the footer
}else
{
	header('location: ../index.php'); 
}
 ob_end_flush(); 
?>