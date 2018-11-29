<?php 
	ob_start();
	session_start();
	include 'includes/languages/english.php';	// languages

	$do = isset($_GET['do']) ? FILTER_VAR($_GET['do'] , FILTER_SANITIZE_STRING) : null;
	// page title
	if ($do == 'Add') { $pageTitle = "Add Item"; }
	elseif (!isset($do) && isset($_SESSION['Username']) && !isset($_GET['UserID'])){ $pageTitle = $_SESSION['FullName']; }
	elseif ($do == "Edit") { $pageTitle = "Edit Profile"; }
	elseif (isset($_GET['UserID'])) { $pageTitle = "User Profile"; }
	else { $pageTitle = "Profile"; }
	include "init.php"; // the paths file
	// stop doing auto write function every refresh
	@ $_SESSION['writeStop'] += 1;
	// end stop making auto write function
	if (isset($_SESSION['Username']) && !isset($do) && !isset($_GET['UserID'])) {
		$datas = getData("*" , "users" , "WHERE UserID = ?" , $_SESSION['ID'] , "UserID" , "DESC" , 10);
		// important if to stop auto write function
		if ($_SESSION['writeStop'] < 2){
?>
	<h1 class="text-center auto_write" id="auto_write" data-text="Wellcome Wish You Are Good Today <?php echo $_SESSION['FullName']; ?> "></h1>
<?php }elseif ($_SESSION['writeStop'] >= 2) {
	echo "<h1 class='text-center'>Wellcome Again</h1>";
} foreach($datas as $data): ?>

<div class="container">

		<div class="panel panel-default container_info">
			<div class="panel-heading">personal Information</div>
			<div class="panel-body">
				<ul class="list-unstyled">
				<li class="lead"><i class="fa fa-unlock-alt fa-fw"></i> Your Username :<strong> <?php echo $data['Username']; ?></strong></li>
				<li class="lead"><i class="fa fa-user-circle fa-fw"></i> Your Full Name :<strong> <?php echo $data['FullName']; ?></strong></li>
				<li class="lead"><i class="fa fa-envelope fa-fw"></i> Your Email Address :<strong> <?php echo $data['Email']; ?></strong></li> 
				<li class="lead"><i class="fa fa-user-plus fa-fw"></i> Your Register Status :<strong> <?php if ($data['RegStatus'] > 0) { echo "Activate"; }else { echo "Unactive :("; } ?></strong></p>
				<li class="lead"><i class="fa fa-calendar fa-fw"></i> Your Registered Date :<strong> <?php echo $data['Regdate']; ?></strong></li>
				<li class="lead"><i class="fa fa-tag fa-fw"></i> Your Fav Category :<strong> 0</strong></li>
				</ul>
				<a href="?do=Edit" class="btn btn-default">Edit My Information <i class="fa fa-edit fa-fw"></i> </a>
			</div> 
		</div>
<?php  
endforeach; ?> 
<!-- start your added items section -->
		<div class="panel panel-default container_items" id="my-items">
			<div class="panel-heading">Your items <a href="?do=Add" class="btn btn-success">Add Item <i class="fa fa-plus"></i> </a></div>
			<div class="panel-body">
	<?php

	// pagination and select items
	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;
	$perpage = isset($_GET['perpage']) && $_GET['perpage'] <= 6 ? (int)$_GET['perpage'] : 3 ;
	$start = $page > 1 ? ($page * $perpage) - $perpage : 0 ;
	// select items 
	$items = getData("items.* , categories.Name AS cat , categories.ID" , "items" , "WHERE Member_ID = ?", $_SESSION['ID'] , "Item_ID" , "DESC" , "{$start} , {$perpage}" , "items&users&categories");

	// know number of pages
	$total = $con->query("SELECT FOUND_ROWS() as total")->fetch()['total'];
	$pages = ceil($total / $perpage); 
	// end pagination
	// important variable
		if (!empty($items)) { ?>
			<div class="row">
		<?php
		foreach ($items as $item) {
		// get comments number
		$stmt2 = $con->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM comments WHERE item_id = ? AND status = 1");
		$stmt2->execute(array($item['Item_ID']));
		$commentsNom_my_profile = $con->query("SELECT FOUND_ROWS() AS numbers")->fetch()['numbers'];
	?>
			<div class="col-sm-6 col-md-4 items_container_space">
				<div class="items">
					<a class="href_items" href="items.php?catid=<?php echo $item['Cat_ID']; ?>&username=<?php echo str_replace(" ","-",$_SESSION['FullName']); ?>&itemid=<?php echo $item['Item_ID']; ?>&itemname=<?php echo str_replace(" ", "-", $item['Name']); ?>&catname2=<?php echo str_replace(" ", "-", $item['cat']) ?>">

						<img src="layout/images/apple.png" class="item_img img-responsive img-center">

						<?php if (isset($_SESSION['Admin']) && $_SESSION['GroupID'] > 0) { ?>
							<!-- Edit item for admin -->
							<a class='edit_item' href="admin/items.php?do=Edit&itemID=<?php echo $item['Item_ID'] ?>"><i class="fa fa-edit"></i></a>
							<!-- Delete Item for admin -->
							<a class='delete_item confirm' href="admin/items.php?do=Delete&itemID=<?php echo $item['Item_ID'] ?>"><i class="fa fa-close"></i></a>
						<?php }elseif (isset($_SESSION['Username']) && !isset($_SESSION['Admin']) && isset($_SESSION['ID']) && $_SESSION['ID'] == $item['Member_ID']) {
						?>
						<!-- Edit item for User -->
						<a class='edit_item' href="profile.php?do=edit-item&item_edit_id=<?php echo $item['Item_ID'] ?>&userid=<?php echo $_SESSION['ID'] ?>"><i class="fa fa-edit"></i></a>
						<!-- Delete Item for User -->
						<a class='delete_item confirm' href="admin/items.php?do=Delete&itemID=<?php echo $item['Item_ID'] ?>"><i class="fa fa-close"></i></a>
						<?php
						} ?>
					</a>
					<ul class="list-unstyled">

					<li>
						<h3
						 class="item_title"><a class="href_items" 
						 href="items.php?catid=<?php echo $item['Cat_ID']; ?>&username=<?php echo str_replace(" ","-",$_SESSION['FullName']); ?>&itemid=<?php echo $item['Item_ID']; ?>&itemname=<?php echo str_replace(" ", "-", $item['Name']); ?>&catname2=<?php echo str_replace(" ", "-", $item['cat']) ?>"> 
						 	<?php echo $item['Name']; ?> 
						</h3>
						</a>
					</li>
					<li class="price_veiws_comments">
						<div class="price_div">Price <i class="fa fa-money fa-fw fa-md"></i> : <span class="price">$<?php echo $item['Price']; ?> </span><?php 
						if ($item['approve'] == 0) { ?>
							<span class="item_noaprove">Waiting approve <i class="fa fa-check fa-fw"></i></span>
						<?php }elseif ($item['approve'] !== 0) { ?>
							<span class="item_aproved">Approved <i class="fa fa-check fa-fw"></i></span>
						<?php } ?></div>

						<span class="comments">Comments : <?php echo $commentsNom_my_profile; ?> <i class="fa fa-comments fa-fw"></i> </span>
						<span class="views">Views : <?php echo $item['count_num']; ?> <i class="fa fa-eye fa-fw"></i> </span>
						<div class="fix"></div>
					</li>
					<li>
						<div class="item_description" style="word-wrap: break-word;"><?php echo limit_text($item['Description'] , 23); ?></div>
					</li>	
					
					<div class="member_date">
						<span>Sold by <i class="fa fa-user fa-fw"></i> : <a href="profile.php?userid=<?php echo $item['Member_ID']; ?>" class="item_member"><?php echo $data['Username']; ?></a></span>
						<span class="pull-right">Date  <i class="fa fa-calendar fa-fw"></i>  : <span class="item_date"><?php echo $item['Add_Date']; ?></span></span>
					</div>
					</ul>
				</div>
			</div>

	<?php 	
			}
	?>
			</div>
		<div class="paginationa">
	<?php
		for ($i = 1 ; $i <= $pages ; $i++){
	?>
		<a href="profile.php?page=<?php echo $i; ?>&perpage=<?php echo $perpage; ?>" <?php if ($i == $page) { echo "class='paginationactive'"; }; ?>><?php echo $i; ?></a>	
	<?php }; ?>
		</div>
	<?php }elseif (empty($items)) {
			echo "<div class='nice_message'>Sorry no items to show :( </div> ";
		} ?>
		</div>
	</div>

	<!-- start your added comemnts section -->
	<?php
		$page1 = isset($_GET['page1']) ? (int)$_GET['page1'] : 1 ;
		$perpage1 = isset($_GET['perpage1']) && $_GET['perpage1'] <= 6 ? (int)$_GET['perpage1'] : 3 ;
		$start1 = $page1 > 1 ? ($page1 * $perpage1) - $perpage1 : 0 ;
		$comments = getData("comments.* , items.Name , categories.Name as cat" , "comments" , "WHERE user_id = ?" , $data['UserID'] , "c_id" , "DESC" , "{$start1},{$perpage1}" , "comments&users&items&categories");

		$total2 = $con->query("SELECT FOUND_ROWS() AS total2")->fetch()['total2']; 
		$pages1 = ceil($total2 / $perpage1);

	?>

		<div class="panel panel-default comment_container_main" id="my-comments">
			<div class="panel-heading">Your latest comments</div>
			<div class="panel-body">
	<?php 
		if (!empty($comments)) {
			foreach ($comments as $comment) {
	?>
		<h3 class="text-center"><i class="fa fa-tag"></i> Item : <a href="items.php?catid=<?php echo $item['ID'] ?>&username=<?php echo str_replace(" ","-",$_SESSION['FullName']); ?>&itemid=<?php echo $comment['item_id']; ?>&itemname=<?php echo str_replace(" ", "-", $comment['Name']); ?>&catname2=<?php echo str_replace(" ", "-", $comment['cat']) ?>"> <?php echo $comment['Name']; ?></a></h3>
		<div class="comment_container">
			<span class="ucomment">Your Comment : </span>
			<span class="comment"><?php echo $comment['comment']; ?></span>
		</div>
	<?php
			}
			echo '<div class="paginationa">';
			for ($x = 1 ; $x <= $pages1 ; $x++) { 
	?>
				<a href="profile.php?page1=<?php echo $x; ?>&perpage1=<?php echo $perpage1; ?>" 
				<?php if ($x == $page1) { echo "class='paginationactive'"; } ?> > <?php echo $x ?></a>
	<?php		
			}
			echo "<div>";
		}elseif (empty($comments)) {
			echo "<div class='nice_message'>Sorry no Comments to show :( </div> ";
		}
	?>

		</div>
		</div>
</div>
<?php
	}




	// Edit page start
	if (isset($_SESSION['Username']) && $do == "Edit")
	{
		$stmt = $con->prepare(" SELECT * FROM users WHERE Username = ? ");
		$stmt->execute(array($_SESSION['Username']));
		$data = $stmt->fetch();
?>
	<h1 class="text-center">Edit Profile <?php echo $_SESSION['Username']; ?></h1>
		<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">Edit personal Information</div>
				<div class="panel-body">
				<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype='multipart/form-data'>
					<div class="row">
					<!-- user name -->
					<div class="form-group form-group-lg">
						<label class="col-md-2 control-label"><?php echo lang('USERNAME') ?> : </label>
						<div class="col-md-4">
							<input type="text" name="Username" class="form-control" required="required"autocomplete="off" value="<?php echo $data['Username'] ?>" />
							<input type="hidden" name="UserID" value="<?php echo $UserID ?>"><!-- Hidden userid -->
						</div>
						<!-- Password name -->
						<label class="col-md-2 control-label"><?php echo lang('PASSWORD') ?> : </label>
						<div class="col-md-4">
							<input type="password" name="Password" placeholder="Click here to Change password" class="form-control" autocomplete="new-password">
						</div>
					</div>
					<!-- email name -->
					<div class="form-group form-group-lg">
						<label class="col-md-2 control-label"><?php echo lang('EMAIL') ?> : </label>
						<div class="col-md-4">
							<input type="email" name="email" class="form-control" required="required" value="<?php echo $data['Email'] ?>" />
						</div>
					<!-- FullName name -->
						<label class="col-md-2 control-label"><?php echo lang('FULLNAME') ?> : </label>
						<div class="col-md-4">
							<input type="text" name="FullName" class="form-control" required="required" value="<?php echo $data['FullName'] ?>" />
						</div>
					</div>
					<!-- image -->
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
					<!-- Submit -->
					<div class="form-group">
						<div class="col-md-4 col-md-push-4">
						<input type="submit" value="<?php echo lang('SAVE') ?>" class="btn btn-primary btn-block" name="updatemember">
						</div>
					</div>
				</div>

				</form>
			</div>
			</div>
		</div>
<?php
	}
	// Start Insert 
	if (isset($_SESSION['Username']) && $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['updatemember']))
	{
		// get data 
		$Username 		= $_POST['Username'];
		$FullName 		= $_POST['FullName'];
		$Email 			= $_POST['email'];
		$Password 		= $_POST['Password'];
		$hashedPass 	= sha1($_POST['Password']);
		$imagename 		= $_FILES['image']['name'];
		$imagetmpname 	= $_FILES['image']['tmp_name'];
		$imagesize 		= $_FILES['image']['size'];
		@$imagetype		= strtolower(end(explode("." , $imagename)));
		// edit image name
		$image = rand(1 , 10000000) . $imagename;
		// start allowed images
		$image_allow = array("png" , "jpg" , "jpeg");
		// start errors checker
		// check if username exist or not
		if ($_SESSION['Username'] !== $Username){
			$stmt_name = $con->prepare("SELECT Username FROM users WHERE Username = ?");
			$stmt_name->execute(array($Username));
			$found_admin = $stmt_name->rowCount(); 
		}
		$formErrors = array();
			if (strlen($Username) < 3)
			{
				$formErrors[] = "Username Can't be <strong> Less Than 3 </strong> Characters";
			}
			//username
			if (strlen($Username) > 20)
			{
				$formErrors[] = "Username Can't be <strong> more Than 20 </strong> Characters";
			}
			//username
			if (empty($Username))
			{
				$formErrors[] = "Username Can't be <strong> Empty </strong>";
			}

			// password
			if (!empty($Password) && strlen($Password) < 6 )
			{
				$formErrors[] = "Password Can't be Less Than <strong> 6 numbers or Characters </strong>";
			}
			//full name
			if (empty($FullName)) {
				$formErrors[] = "Your full name shouldn't be <strong> empty </strong>";
			}
			//full name
			if (strlen($FullName) < 6) {
				$formErrors[] = "Your full name shouldn't be Less than <strong> 6 Characters </strong>";
			}
			// email
			if (empty($Email)) {
				$formErrors[] = "Yoor Email Can't be <strong> empty </strong>";
			}
			//image
			if (!empty($imagename) && ! in_array($imagetype , $image_allow))
			{
				$formErrors[] = "Only images Allowd Types <strong> png , jpg , jpeg </strong>";
			}
			if ($imagesize > 2097152)
			{
				$formErrors[] = "Max Image Size Is <strong>2MP</strong>";
			}
			if (isset($found_admin) && $found_admin > 0)
			{
				$formErrors[] = "Username Allready <strong>Exists</strong>";
			}
			// loop and echo the errors
			foreach ($formErrors as $error) {
				echo '<div class="container"><div class="nice_message">Warning Failed to Update : '.$error . '</div></div>';
			}
			

		// check password way if empty and no errors
		if (empty($Password) && empty($formErrors)) {
			// send data to the database
			move_uploaded_file($imagetmpname , "uploads//" . $image);
			$stmt = $con->prepare("UPDATE users SET Username = ? , Email = ? , FullName = ? , image = ? WHERE UserID = ? ");
			$stmt->execute(array($Username , $Email , $FullName , $image , $_SESSION['ID']));
			if ($stmt->rowCount() > 0) {
				// send the image
				$alert_success1 = "Updated successfully";
				redirectAlert();
				$_SESSION['Username'] = $Username ; // update the Session
				$_SESSION['FullName'] = $FullName ;	// update the Session
				header("Refresh:0");
			}else { echo "<div classcontainer><div class='nice_message'>Something went wrong try again later : or you didn't change any info></div></div>"; }
		}
		
		// if password not empty
		elseif (empty($formErrors) && !empty($Password)) {
			move_uploaded_file($imagetmpname , "uploads//" . $image);
			$stmt = $con->prepare("UPDATE users SET Username = ? , Email = ? , FullName = ? , Password = ? , image = ? WHERE UserID = ? ");
			$stmt->execute(array($Username , $Email , $FullName , $hashedPass , $image ,$_SESSION['ID']));
			if ($stmt->rowCount() > 0) {
				// send the image
				$alert_success1 = "Updated successfully";
				redirectAlert();
				$_SESSION['Username'] = $Username ;	// update the Session
				$_SESSION['FullName'] = $FullName ;	// update the Session
				header("Refresh:0");
			}else { echo "<div classcontainer><div class='nice_message'>Something went wrong try again later : or you didn't change any info</div></div>"; }
		}
	}









// start Add form item
	elseif (isset($_SESSION['Username']) && $do == 'Add') {	
	// insert the new item
	if (isset($_SESSION['Username']) && isset($_POST['insertitem']) && $do == "Add") {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// get data
			$Name 			= filter_var($_POST['Name'] , FILTER_SANITIZE_STRING);
			$Description 	= filter_var($_POST['Description'] , FILTER_SANITIZE_STRING);
			$Price 			= filter_var($_POST['Price'] , FILTER_SANITIZE_NUMBER_INT);
			$Country_Made 	= filter_var($_POST['Country_Made'] , FILTER_SANITIZE_STRING);
			$Status 		= filter_var($_POST['Status'] , FILTER_SANITIZE_NUMBER_INT);
			$Quantity 		= filter_var($_POST['Quantity'] , FILTER_SANITIZE_NUMBER_INT);
			$category 		= filter_var($_POST['category'] , FILTER_SANITIZE_STRING);
			$tags 			= filter_var($_POST['tags'] , FILTER_SANITIZE_STRING);
			$imagename		= filter_var($_FILES['image']['name'] , FILTER_SANITIZE_STRING);
			$imagetmpname	= filter_var($_FILES['image']['tmp_name'] , FILTER_SANITIZE_STRING);
			$imagesize		= filter_var($_FILES['image']['size'] , FILTER_SANITIZE_STRING);
			// get the type
			@$imagetype		= strtolower(end(explode("." , $imagename)));
			// create iamge random name
			$image = rand(1 , 10000000) . $imagename;
			// allowed image types
			$image_type = array("png" , "jpg" , "jpeg");
			// start replace and putting links etc 
			$desc = str_replace(
				array("***" , "**" , "/*"/* this is link code */, 
				"===" , "==" /* h2 title or important point */, 
				"#1#" , "#1" /* ordered list */ ,
				"###" , "/#"/* unordered list */ , 
				"(((" , "))" /* the li code */ ,
				"!*!" , "!!" /* img code */ , 
				"\n" /* <br> code */ ) , 
				array("<a href='" , "' target='_blank'>" , "</a>" /* link code */, 
				"<span class='h3 item_subtitle_info' style='display:block;'>" , "</span>" /* h2 title */ , 
				"<ol>" , "</ol>" /* ordeed list */ , 
				"<ul>" , "</ul>" /* un ordered list */ , 
				"<li>" , "</li>" /* this is li code */ , 
				"<img class='img-responsive' style='margin:0 auto;' src='" , "'>" /* this img code */ ,
				"<br>" /* this is new line code */ ) , 
				$Description
			);
			// check errors array
			$Items_errors = array();
			// check all inputs errors
			// check item name
			if (empty($_POST['Name'])) {
				$Items_errors[] = "You Shouldn't Leave Item Name Empty";
			}
			if (!empty($_POST['Name']) &&strlen($_POST['Name']) < 4) {
				$Items_errors[] = "Item Name is too short";
			}
			// check Description
			if (empty($_POST['Description'])) {
				$Items_errors[] = "You Shouldn't Leave Description Empty";
			}
			// check price
			if (!is_numeric($Price)) {
				$Items_errors[] = "You Should't leave Item price empty or write characters on it";
			}
			// check Country made in
			if (empty($_POST['Country_Made'])) {
				$Items_errors[] = "You souldn't Leave The Made IN Country Empty ";
			}
			// check status
			if ($_POST['Status'] == 0) {
				$Items_errors[] = "Please Choose One of the 4 status you can't leave it without choose you can't chosse Select one of status under";
			}
			// check Quantity
			if (empty($_POST['Quantity'])) {
				$Items_errors[] = "Just Write How Many items you have or wanna sell ";
			}
			if (!empty($_POST['Quantity']) && !is_numeric($_POST['Quantity'])) {
				$Items_errors[] = "You can input only numbers in the Quantity Field";
			}
			if ($_POST['Quantity'] == 0) {
				$Items_errors[] = "You can't Write only 0 in this field";
			}
			// category
			if ($_POST['category'] == 0) {
				$Items_errors[] = "please you sould choose a category";	 
			}
			// image
			if(!empty($imagename) && ! in_array($imagetype , $image_type))
			{
				$Items_errors[] = "Only Images Allowed With type <strong> PNG , JPG , JPEG </strong>";
			}
			if($imagesize > 2097152)
			{
				$Items_erros[] = "Max Image width is <strong> 2MP</strong>";
			}
			// echo errors if there were errors
			foreach ($Items_errors as $error) {
				echo '<div class="alert alert-danger text-center">'.$error. '</div>';
			}	
			if (!empty($Items_errors)) {
				$alert_danger4 = "Please read the errors above";
				$alert_redirect_back = "active";
				$seconds = 1 ;
				redirectAlert();				
			}
			// if there's no error insert data
			elseif (empty($Items_errors)) {

				$stmt2 = $con->prepare("
					INSERT INTO 
					items (Name , Description , Price , Add_Date , Status ,Country_Made , Quantity , approve , Cat_ID , Member_ID , count_num , tags , image) VALUES ( ? , ? , ? , now() , ? , ? , ? , 0 , ? , ? , 0 , ? , ?)");
				$stmt2->execute(array($Name , $desc , $Price , $Status , $Country_Made , $Quantity , $category , $_SESSION['ID'] , $tags , $image));
				$count = $stmt2->rowCount();
				// send image to the items folder
				move_uploaded_file($imagetmpname , "items//".$image);
				if ($count > 0) {
					$alert_success5 = "Item Added Successfully Congratulations";
				}elseif ($count = 0) {
					$alert_danger3 = "SomeThing Went Wrong";
					$alert_redirect_home = "active";
				}else
				{
					$alert_danger3 = "SomeThing Went Wrong";
					$alert_redirect_home = "active";
				}
			}

		}
	}

?>
	
		<h1 class="text-center"> Add New Item </h1>
		<?php 
			$alert_warning1 = "All Fields with <span style='color:red;'> red </span>star are Required";
			redirectAlert();
		 ?>
		<div class="container">
		<div class="pnel panel-default item_panel">
			<div class="panel-heading">Create New Ad <i class="fa fa-plus"></i></div>
		<div class="panel-body">
			<div class="row">
			<form class="form-horizontal" method="POST" action="?do=Add" id="add_item_form" enctype='multipart/form-data'>
				<div class="col-md-8">
					<div class="form-group">
						<!-- Input Item Name -->
						<label class="control-label col-md-3"><?php echo lang('NAME') ?> : </label>
						<div class="col-md-9">

						<input type="text" name="Name" class="form-control live" required="required"  placeholder="Write Item Name ..." id="textarea_name" data-class=".live_title">


						</div>
						<!-- Input Item Description -->
					</div>
					
						<label class="control-label col-md-3 col-md-push-4"><?php echo lang('DESCRIPTION') ?> : </label>
						<div class="col-md-12">

						<div class="add_item_input_style_container">
							<span class="changer link_item" data-text=" *** Your link here ** link name /* " title="Add Link">
								<span class="hidden-xs">Add link</span> 
							<i class="fa fa-link fa-fw"></i></span>
							<span class="changer link_subtitle" data-text=" === Your title here == " title="Add Subtitle">
								<span class="hidden-xs">Add subtitle</span>
								<i class="fa fa-header fa-fw"></i></span> 
							<span class="changer link_image" data-text=" !*! Your img link !! " title="Add Image">
								<span class="hidden-xs">Add Image</span> 
								<i class="fa fa-image fa-fw"></i></span> 
<span class="changer link_ollist" data-text="
#1#
((( 1 ))
((( 2 ))
((( 3 ))
#1 
" title="Add Ordered list option">
<span class="hidden-xs">Ordered List</span>
<i class="fa fa-list-ol fa-fw"></i></span> 
<span class="changer link_ullist" data-text="
###
((( input ))
(((   ))
(((   ))
/# 
" title="Add Unordered list Option">
<span class="hidden-xs">Unordered List</span>
							 <i class="fa fa-list-ul fa-fw"></i></span> 

							<textarea 
							class="form-control hidden realtext" 
							required="required"  
							placeholder="Descripe The Item ..." 
							name="Description"  
							style="height: 35px;max-height: 300px;" 
							></textarea>
							<textarea 
							class="form-control faketext live add_item_input_style" 
							required="required"  
							placeholder="Descripe The Item ..." 
							name="Description1" 
							data-class=".live_desc" 
							></textarea>
						</div>
						
					</div>
					<div class="form-group">
						<!-- Start Price Field -->
						<label class="control-label col-md-3">Price $ : </label>
						<div class="col-md-8">
							<input type="test" name="Price" required="required"  placeholder="Write Free it item is free..." class="form-control item_price live" title="The items price equal in dollars" data-class=".live_price">
						</div>	
						</div>
						<div class="form-group">
						<!-- Start Made In Country Field -->	
						<label class="control-label col-md-3">Made In <i class="fa fa-building fa-fw"></i>: </label>
						<div class="col-md-8">
							<input type="text" name="Country_Made"  class="form-control" required="required"  placeholder="example .. England">
						</div>
					</div>
					<div class="form-group">
						<!-- start Item Status good or old -->
						<label class="control-label col-md-3">Item Status : </label>
						<div class="col-md-8">
							<select name="Status" required="required" style="width: 100%;">
								<option value="0">Select one of status under</option>
								<option value="1">New</option>
								<option value="2">Like New</option>
								<option value="3">Used</option>
								<option value="4">Old</option>
							</select>
						</div>
						</div>
						<div class="form-group">
						<label class="control-label col-md-3">Quantity : </label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="Quantity" required="required" placeholder="Home many Items do you have .. ">
						</div>
					</div>
					<!-- Start Choose user option -->
					<div class="form-group">
						<!-- Start Category select box -->
						<label class="col-md-3 control-label">Category :</label>
						<div class="col-md-8">
							<select required="required" name="category" style="width: 100%;">
								<option value="0">Select The Seller Of The Item</option>
								<?php 
									$stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0");
									$stmt2->execute();
									$cats = $stmt2->fetchAll();
									foreach ($cats as $cat) {
									$soncats = getData("*" , "categories" , "WHERE parent = ?" , $cat['ID'] , "Ordring" , "ASC" ,"" , "");
										echo "<option value='".$cat['ID'] ."'>".$cat['Name'] ."</option>";
									if (!empty($soncats)):
										foreach ($soncats as $c){
										echo "<option value='".$c['ID'] ."'>--> ".$c['Name'] ."</option>";
										}
									endif;
									}
								 ?>
							</select>
						</div>
					</div>
					<!-- Start add Tags input field -->
					<div class="form-group">
						<label class="col-md-3 control-label">Tags : </label>
						<div class="col-md-8">
							<input type="text" name="tags" class="form-control" placeholder="Use (,) between each tag phones,iphone,apple,cheap...">
						</div>
					</div>
					<!-- image -->
					<div class="form-group">
						<label class="col-md-3 control-label">User Image : </label>
						<div class="col-md-8 user_image_container">
							<div class="user_image_background">
							<input type="file" name="image" class="user_image form-control">
							<span class="user_image_alert">Max <i class="fa fa-image fa-fw"></i> Size 2MP</span>
							<span class="btn btn-default">Upload Image<i class="fa fa-upload fa-fw"></i></span>
							</div>
						</div>
					</div>
						<!-- Input submit -->
						<div class="col-md-4 col-md-push-4">
							<input type="submit" value="<?php echo lang('ADD') ?>" class="btn btn-primary btn-block send_item" name="insertitem">
						</div>
					</div>
				</form>
				<!-- How the item woulb be when you finish it -->

			<div class="items_border_inside col-sm-6 col-md-4">	

				<div class="items">
						<img src="layout/images/test.jpg" class="item_img img-responsive img-center">
					<ul class="list-unstyled">
						<li>
							<h3 class="item_title live_title">Your title</h3>
						</li>
						<li class="price_veiws_comments">
							<div class="price_div">Price <i class="fa fa-money fa-fw fa-md"></i> : <span class="price">$<span class="live_price">0</span></span></div>
							<span class="comments">Comments : 0 <i class="fa fa-comments fa-fw"></i> </span>
							<span class="views">Views : 0 <i class="fa fa-eye fa-fw"></i> </span>
							<div class="fix"></div>
						</li>
						<li>
							<div class="item_description live_desc" style="word-wrap: break-word;">You Description</div>
						</li>
						<li class="member_date">
						<span>Sold by <i class="fa fa-user fa-fw"></i> : <span class="item_member"><?php echo $_SESSION['Username']; ?></span></span>
						<span class="pull-right">Date <i class="fa fa-calendar fa-fw"></i> : <span class="item_date"><?php echo date("Y-m-d"); ?></span></span>
						</li>
					</ul>

				</div>
			</div>
			</div>
		</div>
		</div>
		</div>
<?php 	
	}
// end add items
	// get item id
	$item_edit_id = isset($_GET['item_edit_id']) ? (int)$_GET['item_edit_id'] : 0 ;
	// start Edit item
if (isset($_GET['do']) && isset($_GET['userid']) && isset($_SESSION['ID']) &&  filter_var($_GET['do'], FILTER_SANITIZE_STRING) == "edit-item" && $_GET['userid'] == $_SESSION['ID'] && $item_edit_id > 0) {
		// make sure if the user is the owner of the item
		$stmt_prove = getData("items.* , categories.Name as cat , categories.ID" , "items" , "WHERE Item_ID = $item_edit_id AND Member_ID = ?" , $_SESSION['ID'] , "Item_ID" , "" , 1 , "items&users&categories");

		// if item exists do all of this shit
	if (!empty($stmt_prove)) {
				if (isset($_SESSION['Username']) && isset($_POST['updateitem'])) {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// get data
			$Name 			= filter_var($_POST['Name'] , FILTER_SANITIZE_STRING);
			$Description 	= filter_var($_POST['Description'] , FILTER_SANITIZE_STRING);
			$Price 			= filter_var($_POST['Price'] , FILTER_SANITIZE_NUMBER_INT);
			$Country_Made 	= filter_var($_POST['Country_Made'] , FILTER_SANITIZE_STRING);
			$Status 		= filter_var($_POST['Status'] , FILTER_SANITIZE_NUMBER_INT);
			$Quantity 		= filter_var($_POST['Quantity'] , FILTER_SANITIZE_NUMBER_INT);
			$category 		= filter_var($_POST['category'] , FILTER_SANITIZE_STRING);
			$tags 			= filter_var($_POST['tags'] , FILTER_SANITIZE_STRING);
			// start replace and putting links etc 
			$desc = str_replace(
				array("***" , "**" , "/*"/* this is link code */, 
				"===" , "==" /* h2 title or important point */, 
				"#1#" , "#1" /* ordered list */ ,
				"###" , "/#"/* unordered list */ , 
				"(((" , "))" /* the li code */ ,
				"!*!" , "!!" /* img code */ , 
				"\n" /* <br> code */ ) , 
				array("<a href='" , "' target='_blank'>" , "</a>" /* link code */, 
				"<span class='h3 item_subtitle_info'>" , "</span>" /* h2 title */ , 
				"<ol>" , "</ol>" /* ordeed list */ , 
				"<ul>" , "</ul>" /* un ordered list */ , 
				"<li>" , "</li>" /* this is li code */ , 
				"<img class='img-responsive' style='margin:0 auto;' src='" , "'>" /* this img code */ ,
				"<br>" /* this is new line code */ ) , 
				$Description
			);
			// check errors array
			$Items_errors = array();
			// check all inputs errors
			// check item name
			if (empty($_POST['Name'])) {
				$Items_errors[] = "You Shouldn't Leave Item Name Empty";
			}
			if (!empty($_POST['Name']) &&strlen($_POST['Name']) < 4) {
				$Items_errors[] = "Item Name is too short";
			}
			// check Description
			if (empty($_POST['Description'])) {
				$Items_errors[] = "You Shouldn't Leave Description Empty";
			}
			// check price
			if (!is_numeric($Price)) {
				$Items_errors[] = "You Should't leave Item price empty or write characters on it";
			}
			// check Country made in
			if (empty($_POST['Country_Made'])) {
				$Items_errors[] = "You souldn't Leave The Made IN Country Empty ";
			}
			// check status
			if ($_POST['Status'] == 0) {
				$Items_errors[] = "Please Choose One of the 4 status you can't leave it without choose you can't chosse Select one of status under";
			}
			// check Quantity
			if (empty($_POST['Quantity'])) {
				$Items_errors[] = "Just Write How Many items you have or wanna sell ";
			}
			if (!empty($_POST['Quantity']) && !is_numeric($_POST['Quantity'])) {
				$Items_errors[] = "You can input only numbers in the Quantity Field";
			}
			if ($_POST['Quantity'] == 0) {
				$Items_errors[] = "You can't Write only 0 in this field";
			}
			// category
			if ($_POST['category'] == 0) {
				$Items_errors[] = "please you sould choose a category";
				 
			}
			// echo errors if there sere errors
			foreach ($Items_errors as $error) {
				echo '<div class="alert alert-danger text-center">'.$error. '</div>';
			}	
			if (!empty($Items_errors)) {
				$alert_danger4 = "Please read the errors above";
				$alert_redirect_back = "active";
				$seconds = 1 ;
				redirectAlert();				
			}
			// if there's no error insert data
			elseif (empty($Items_errors)) {

				$stmt2 = $con->prepare("
					UPDATE items 
					SET Name = ? , Description = ? , Price = ? , Add_Date = now() , Status = ? ,Country_Made = ? , Quantity = ? , tags = ? WHERE Member_ID = ? AND Item_ID = ?");
				$stmt2->execute(array($Name , $desc , $Price , $Status , $Country_Made , $Quantity , $tags , $_SESSION['ID'] , $item_edit_id));
				$count = $stmt2->rowCount();
				if ($count > 0) {
					$alert_success5 = "Item Added Successfully Congratulations";
					header("refresh:0;profile.php?do=edit-item&item_edit_id=".$item_edit_id."&userid=".$_SESSION['ID']);
				}elseif ($count = 0) {
					$alert_danger3 = "SomeThing Went Wrong";
					$alert_redirect_home = "active";
				}else
				{
					$alert_danger3 = "SomeThing Went Wrong";
					$alert_redirect_home = "active";
				}
			}

		}
	}
	if (!empty($stmt_prove))
	{
		foreach ($stmt_prove as $item) {
				// return the str values to its main
				$desc_edit = str_replace(
				array(
				"<a href='" , "' target='_blank'>" , "</a>" /* link code */, 
				"<span class='h3 item_subtitle_info'>" , "</span>" /* h2 title */ , 
				"<ol>" , "</ol>" /* ordeed list */ , 
				"<ul>" , "</ul>" /* un ordered list */ , 
				"<li>" , "</li>" /* this is li code */ , 
				"<img class='img-responsive' style='margin:0 auto;' src='" , "'>" /* this img code */ ,
				"<br>" /* this is new line code */ ),
				array(
				"***" , "**" , "/*"/* this is link code */, 
				"===" , "==" /* h2 title or important point */, 
				"#1#" , "#1" /* ordered list */ ,
				"###" , "/#"/* unordered list */ , 
				"(((" , "))" /* the li code */ ,
				"!*!" , "!!" /* img code */ , 
				"\n" /* <br> code */ ) , 
  
				$item['Description']
			);
?>
	
		<h1 class="text-center"> Update / Edit Item </h1>
		<?php 
			$alert_warning1 = "All Fields with <span style='color:red;'> red </span>star are Required";
			redirectAlert();
		 ?>
		<div class="container">
		<div class="pnel panel-default item_panel">
			<div class="panel-heading">Edit Item <i class="fa fa-edit fa-fw"></i></div>
		<div class="panel-body">
			<div class="row">
			<form class="form-horizontal" method="POST" action="profile.php?do=edit-item&item_edit_id=<?php echo $item['Item_ID'] ?>&userid=<?php echo $_SESSION['ID'] ?>" id="add_item_form">
				<div class="col-md-12">
					<div class="form-group">
						<!-- Input Item Name -->
						<label class="control-label col-md-2"><?php echo lang('NAME'); ?> : </label>
						<div class="col-md-10">

						<input type="text" name="Name" value="<?php echo $item['Name']; ?>" class="form-control live" required="required"  placeholder="Write Item Name ..." id="textarea_name" data-class=".live_title">


						</div>
						<!-- Input Item Description -->
					</div>
					
						<label class="control-label col-md-3 col-md-push-4"><?php echo lang('DESCRIPTION'); ?> : </label>
						<div class="col-md-12">

						<div class="add_item_input_style_container">
							<span class="changer link_item" data-text=" *** Your link here ** link name /* " title="Add Link">
								<span class="hidden-xs">Add link</span> 
							<i class="fa fa-link fa-fw"></i></span>
							<span class="changer link_subtitle" data-text=" === Your title here == " title="Add Subtitle">
								<span class="hidden-xs">Add subtitle</span>
								<i class="fa fa-header fa-fw"></i></span> 
							<span class="changer link_image" data-text=" !*! Your img link !! " title="Add Image">
								<span class="hidden-xs">Add Image</span> 
								<i class="fa fa-image fa-fw"></i></span> 
<span class="changer link_ollist" data-text="
#1#
((( 1 ))
((( 2 ))
((( 3 ))
#1 
" title="Add Ordered list option">
<span class="hidden-xs">Ordered List</span>
<i class="fa fa-list-ol fa-fw"></i></span> 
<span class="changer link_ullist" data-text="
###
((( input ))
(((   ))
(((   ))
/# 
" title="Add Unordered list Option">
<span class="hidden-xs">Unordered List</span>
							 <i class="fa fa-list-ul fa-fw"></i></span> 

							<textarea 
							class="form-control hidden realtext" 
							required="required"  
							placeholder="Descripe The Item ..." 
							name="Description"  
							style="height: 35px;max-height: 300px;" 
							><?php echo $desc_edit; ?></textarea>
							<textarea 
							class="form-control faketext live add_item_input_style" 
							required="required"  
							placeholder="Descripe The Item ..." 
							name="Description1" 
							data-class=".live_desc" 
							><?php echo $desc_edit; ?></textarea>
						</div>
						
					</div>
					<div class="form-group">
						<!-- Start Price Field -->
						<label class="control-label col-md-2">Price : $</label>
						<div class="col-md-10">
							<input type="test" name="Price" required="required"  placeholder="Write Free it item is free..." class="form-control item_price live" title="The items price equal in dollars" data-class=".live_price" value="<?php echo $item['Price']; ?>">
						</div>	
						</div>
						<div class="form-group">
						<!-- Start Made In Country Field -->	
						<label class="control-label col-md-2">Made In : </label>
						<div class="col-md-10">
							<input type="text" name="Country_Made"  class="form-control" required="required"  placeholder="example .. England" value="<?php echo $item['Country_Made']; ?>">
						</div>
					</div>
					<div class="form-group">
						<!-- start Item Status good or old -->
						<label class="control-label col-md-2">Item Status : </label>
						<div class="col-md-10">
							<select name="Status" required="required" style="width: 100%;">
								<option value="0">Select one of status under</option>
								<option value="1" <?php echo $check = $item['Status'] == 1 ? "selected" : null; ?>>New</option>
								<option value="2" <?php echo $check2 = $item['Status'] == 2 ? "selected" : null; ?>>Like New</option>
								<option value="3" <?php echo $check3 = $item['Status'] == 3 ? "selected" : null; ?>>Used</option>
								<option value="4" <?php echo $check4 = $item['Status'] == 4 ? "selected" : null; ?>>Old</option>
							</select>
						</div>
						</div>
						<div class="form-group">
						<label class="control-label col-md-2">Quantity : </label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="Quantity" required="required" placeholder="Home many Items do you have .. " value="<?php echo $item['Quantity']; ?>">
						</div>
					</div>
					<!-- Start Choose user option -->
					<div class="form-group">
						<!-- Start Category select box -->
						<label class="col-md-2 control-label">Category :</label>
						<div class="col-md-10">
							<select required="required" name="category" style="width: 100%;">
								<option value="0">Select The Seller Of The Item</option>
								<?php 
									$stmt2 = $con->prepare("SELECT * FROM categories");
									$stmt2->execute();
									$cats = $stmt2->fetchAll();
									foreach ($cats as $cat) {
										echo "<option value='".$cat['ID'] ."'";
										if($cat['ID'] == $item['Cat_ID']){echo "selected";};
										echo ">".$cat['Name'] ."</option>";
									}
								 ?>
							</select>
						</div>
					</div>
					<!-- Start add Tags input field -->
					<div class="form-group">
						<label class="col-md-2 control-label">Tags : </label>
						<div class="col-md-10">
							<input value="<?php echo $item['tags']; ?>" type="text" name="tags" class="form-control" placeholder="Use (,) between each tag phones,iphone,apple,cheap...">
						</div>
					</div>
						<!-- Input submit -->
						<div class="col-md-4 col-md-push-4">
							<input type="submit" value="Update" class="btn btn-primary btn-block send_item" name="updateitem">
						</div>
					</div>
				</form>

			</div>
		</div>
		</div>
		</div>
<?php
		}

		}else
		{
			echo "This item isn't yours try another item";
		}
		}
}




// start public profile 
	if (!isset($do) && isset($_GET['UserID'])) {
		// make sure input is a number
		$UserID = isset($_GET['UserID']) ? (int)$_GET['UserID'] : 0 ;
		$stmt2 = $con->prepare("SELECT * FROM users WHERE UserID = {$UserID}");
		$stmt2->execute();
		$users = $stmt2->fetchAll();
		$count_user = $stmt2->rowCount();
		// items data
		// pagination CAlC
		$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 3 ;
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;
		$start = ( $page - 1 ) * $limit ;
		// start getting data
		$items_users = getData("items.* , categories.Name as cat" , "items" , "WHERE Member_ID = ?" , $UserID , "Item_ID" , "" , "{$start},{$limit}" , "items&users&categories");
		// count of the items
		$total = $con->query("SELECT FOUND_ROWS() AS total")->fetch()['total'];
		$pages = ceil($total / $limit);
		// the page title here
		?>
	<div class='container'>
	<?php
		if (!empty($items_users)) {

			foreach($users as $user) {
?>

		<div class="panel panel-default container_info">
			<div class="panel-heading">personal Information</div>
			<div class="panel-body">
				<ul class="list-unstyled">
				<li class="lead"><i class="fa fa-unlock-alt fa-fw"></i> User Username :<strong> <?php echo $user['Username']; ?></strong></li>
				<li class="lead"><i class="fa fa-user-circle fa-fw"></i> User Full Name :<strong> <?php echo $user['FullName']; ?></strong></li>
				<li class="lead"><i class="fa fa-user-plus fa-fw"></i> User Register Status :<strong> <?php if ($user['RegStatus'] > 0) { echo "Activate"; }else { echo "Unactive :("; } ?></strong></p>
				<li class="lead"><i class="fa fa-calendar fa-fw"></i> User Registered Date :<strong> <?php echo $user['Regdate']; ?></strong></li>
				<li class="lead"><i class="fa fa-tag fa-fw"></i> User Fav Category :<strong> 0</strong></li>
				<li class="lead"><i class="fa fa-tags fa-fw"></i> Added Items : <strong><?php echo $total; ?></strong></li>
				</ul>
			</div>
		</div>
		<div class="panel panel-default container_items">
			<div class="panel-heading"><?php echo $user['FullName']; ?> items</div>
			<div class="panel-body">
		
			<div class="row">
<?php 
			}if(!empty($items_users)){
				// items section
		foreach ($items_users as $item) {
						// get comments number
			$stmt2 = $con->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM comments WHERE item_id = ?");
			$stmt2->execute(array($item['Item_ID']));
			$commentsNom = $con->query("SELECT FOUND_ROWS() AS numbers")->fetch()['numbers'];
?>
			<div class="col-sm-6 col-md-4 items_container_space">
			<div class="items">
				<a class="href_items" href="items.php?catid=<?php echo $item['Cat_ID']; ?>&name=<?php echo str_replace(" ","-",$user['Username']); ?>&itemid=<?php echo $item['Item_ID']; ?>&itemname=<?php echo str_replace(" ", "-", $item['Name']); ?>&catname2=<?php echo str_replace(" ", "-", $item['cat']) ?>">
					<img src="layout/images/apple.png" class="item_img img-responsive img-center">

					<?php if (isset($_SESSION['Admin']) && $_SESSION['GroupID'] > 0) { ?>
							<!-- Edit item for admin -->
						<a class='edit_item' href="admin/items.php?do=Edit&itemID=<?php echo $item['Item_ID'] ?>"><i class="fa fa-edit"></i></a>
						<!-- Delete Item for admin -->
						<a class='delete_item confirm' href="admin/items.php?do=Delete&itemID=<?php echo $item['Item_ID'] ?>"><i class="fa fa-close"></i></a>
					<?php }elseif (isset($_SESSION['Username']) && !isset($_SESSION['Admin']) && isset($_SESSION['ID']) && $_SESSION['ID'] == $item['Member_ID']) {
					?>
						<!-- Edit item for User -->
						<a class='edit_item' href="admin/items.php?do=Edit&itemID=<?php echo $item['Item_ID'] ?>"><i class="fa fa-edit"></i></a>
						<!-- Delete Item for User -->
						<a class='delete_item confirm' href="admin/items.php?do=Delete&itemID=<?php echo $item['Item_ID'] ?>"><i class="fa fa-close"></i></a>
					<?php
					} ?>

				</a>
				<ul class="list-unstyled">
					<li>
					<h3 class="item_title">
						<a class="href_items" href="items.php?catid=<?php echo $item['Cat_ID']; ?>&name=<?php echo str_replace(" ","-",$user['Username']); ?>&itemid=<?php echo $item['Item_ID']; ?>&itemname=<?php echo str_replace(" ", "-", $item['Name']); ?>&catname2=<?php echo str_replace(" ", "-", $item['cat']) ?>"> <?php echo $item['Name']; ?> </a>	</h3>
					</li>
						<li class="price_veiws_comments">
							<div class="price_div">Price <i class="fa fa-money fa-fw fa-md"></i> : <span class="price">$<?php echo $item['Price']; ?> </span></div>
							<span class="comments">Comments : <?php echo $commentsNom; ?> <i class="fa fa-comments fa-fw"></i> </span>
							<span class="views">Views : <?php echo $item['count_num']; ?> <i class="fa fa-eye fa-fw"></i> </span>
							<div class="fix"></div>
						</li>
						<li>
							<div class="item_description" style="word-wrap: break-word;"><?php echo limit_text($item['Description'] , 23); ?></div>
						</li>
						<li class="member_date">
							<span>Sold by <i class="fa fa-user fa-fw"></i> : <a href="profile.php?UserID=<?php echo $item['Member_ID']; ?>" class="item_member"><?php echo $user['Username']; ?></a></span>
							<span class="pull-right">Date <i class="fa fa-calendar fa-fw"></i> : <span class="item_date"><?php echo $item['Add_Date']; ?></span></span>
						</li>
				</ul>

			</div>
		</div>
<?php
			}?>
			</div>

<!-- pagination links -->
			<div class='paginationa'>
<?php
			for ($x=1; $x <= $pages ; $x++) { ?>

			 <a href="profile.php?page=<?php echo $x; ?>&limit=<?php echo $limit; ?>&UserID=<?php echo $user['UserID']; ?>" <?php if ($x == $page) { echo "class='paginationactive'"; }; ?>><?php echo $x; ?></a>
<?php
			}
?>
			</div>
		</div>
	</div>
		

<?php
		}else { echo "<div class='nice_message'>There's No items To show for <strong>".$user['Username']."</strong></div>"; }
?>

<?php

		echo "</div>";
		}else
		{
			echo "<div class='nice_message'>something went wrong sorry :/ Or User Dosn't exist</div>";	
		}
	}




include $tpl.'footer.php'; // footer include
// auto write fucntion script auto stop if a member came twice
if ($_SESSION['writeStop'] < 2){
?>
<script type="text/javascript">
		$(document).ready(function(){

			var text =  $(".auto_write").data("text"),
				text1 = $(".auto_write").text(),
				i = 0,
				the_auto_write =
			setInterval(function() {
				//document.getElementById("auto_write").textContent += text[i];
				$(".auto_write").text(text1 += text[i]);
				i += 1 ;
				if (i > text.length - 1) { clearInterval(the_auto_write); };
				;},150);

		})	
</script>
<?php 
}
 ob_end_flush(); 

?>
