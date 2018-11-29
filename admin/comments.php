<?php

	/*
		Categories => [ Manage | Edit | Update | Add | Insert |Delete | Stats ]
	*/
	ob_start();
	/*
	((((((((((((((((((Comments page))))))))))))))))))))))
	**** Manage comments
	**** EDIT comments
	***************************************************	
	*/
	session_start();
	if (isset($_SESSION['Username'])){
	include 'includes/languages/english.php';	// languages 
	$pageTitle =lang('CATEGORIES') ; //title
	include 'init.php';	// the inip page
	}
 	else {header('location: index.php'); }; // if users didn't sign in
// check the admin or user
if (proveAdmin() == 1)
{
	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

	// If The Page Is Main Page

	if ($do == 'Manage') {
		$stmt = $con->prepare("
			SELECT
				comments.* , items.Name AS itemname , users.Username 
			FROM 
				comments
			INNER JOIN 
				items ON items.Item_ID = comments.item_id 
			INNER JOIN 
				users ON users.UserID = comments.user_id
			ORDER BY c_id DESC
		 ");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		if (! empty($rows)) {
?>
	<h1 class="text-center">Manage Comments</h1>
	<div class="container">
		<div class="table-responsive">
			<table class="main-table text-center table table-bordered">
				<tr>
					<td>#ID</td>
					<td>Comment</td>
					<td>Username</td>
					<td>Item</td>
					<td>Control</td>
				</tr>
			<?php
				foreach ($rows as $row) {
					echo "<tr>";
						echo "<td>".$row['c_id']."</td>";
						echo "<td class='comment_show'>";
						echo "<span title='show comment' class='btn btn-success comment_func' ><i class='fa fa-plus'></i> Show</span>";
						echo "<div>".$row['comment']."</div>";
						

						echo "</td>";
						echo "<td>".$row['Username']."</td>";
						echo "<td>".$row['itemname']."</td>";
						echo "<td>";
							echo '<a href="comments.php?do=Edit&comid='.$row['c_id'].'" class="btn btn-success hvr-icon-pulse-grow"><i class="fa fa-edit hvr-icon"></i> '.lang('EDIT').'</a> ';
							echo '<a href="comments.php?do=Delete&comid='.$row['c_id'].'" class="btn btn-danger confirm hvr-icon-pulse-grow"><i class="fa fa-close hvr-icon"></i> '.lang('DELETE').'</a>';

							if ($row['status'] == 0) {
								echo '	<a href="comments.php?do=approve&comid='.$row['c_id'].'" class="btn btn-info hvr-icon-pulse-grow"><i class="fa fa-check hvr-icon"></i> Approve</a>';
							}elseif ($row['status'] == 1) {
								echo '	<a href="comments.php?do=disapprove&comid='.$row['c_id'].'" class="btn btn-primary hvr-icon-pulse-grow" style="font-size:12px;"><i class="fa fa-close hvr-icon"></i> Disapprove</a>';
							}
						echo "</td>";
					echo "</tr>";
				}

			?>
			</table>
		</div>
		<a class="btn btn-success" href="?do=Add">Add Comment</a>
	</div>
<?php 
		}
		else
		{

		echo '<h1 class="text-center">Manage Comments empty :(</h1>
			<div class="container">
			<div class="nice_message">No Comments To Show</div>
			<a class="btn btn-primary" href="?do=Add">Add New Comment</a>
			</div>';
		}
	} 
	elseif ($do == 'Add') {
?>
	<h1 class="text-center">Add Comment</h1>
	<div class="container">
		<?php $alert_warning1 = "All Fields With <span style='color:red;'>Red Stars</span> are Required";
		redirectAlert(); ?>
		<form class="form-horizontal" method="POST" action="?do=Insert">
			<div class="row">
				<!-- Start User name and item's add in post -->
					<div class="form-group">
						<label class="control-label col-md-2">Item : </label>
						<div class="col-md-4">
							<select name="item_id" required="required">
								<option value="0">Choose The item you wanna add commen in.</option>
								<?php
									$stmt = $con->prepare("SELECT * FROM items");
									$stmt->execute();
									$items = $stmt->fetchAll();
									$count = $stmt->rowCount();
									if ($count > 0) {
										foreach ($items as $item) {
											echo "<option value='".$item['Item_ID']."'>".$item['Name']."</option>";
										}
									}
								?>
							</select>
						</div>
						<label class="control-label col-md-2">UserName : </label>
						<div class="col-md-4">
							<select name="user_id" required="required">
								<option value="0">Choose User Name.</option>
								<?php
									$stmt1 = $con->prepare("SELECT * FROM users");
									$stmt1->execute();
									$users = $stmt1->fetchAll();
									$count1 = $stmt1->rowCount();
									if ($count1 > 0) {
										foreach ($users as $user) {
											echo "<option value='".$user['UserID']."'>".$user['Username']."</option>";
										}
									}
								?>
							</select>	
						</div>
					</div>
				<!-- End The items post and usernaem of the adder -->
				<!-- start comment text area -->
				<div class="form-group">
					<label class="control-label col-md-2">Comment : </label>
					<div class="col-md-10">
						<a class="btn btn-primary" id="image_a">add Image</a>
						<textarea name="comment" readonly="readonly" class="form-control" required="required" placeholder="Write Your Comment... real" id="real_comment"></textarea>
						<textarea class="form-control fake_comment" required="required" placeholder="Write Your Comment... " id="textarea_comment"></textarea>
					</div>
				</div>
				<!-- End comment text area -->
				<!-- Start Submit -->
				<div class="form-group">
					<div class="col-md-4 col-md-push-4">
						<input type="submit" value="<?php echo lang('ADD') ?>" class="btn btn-primary btn-block">
					</div>
				</div>
				<!-- End Submit -->


			</div>
		</form>
	</div>
<?php 

	}
	// start Insert page
	elseif ($do == "Insert") {
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			// get Data
			$comment = $_POST['comment'];
			$user_id = $_POST['user_id'];
			$item_id = $_POST['item_id'];
			// get errors
			$comment_errors = array();

			if ($_POST['user_id'] == 0) {
				$comment_errors[] = "Please choose a user no the info";
			}
			if ($_POST['item_id'] == 0) {
				$comment_errors[] = "Please choose a user no the info";
			}
			if (empty($_POST['comment'])) {
				$comment_errors = "You can't leave comemnt area empty just write anything" ;
			}
			// echo errors if exists
			if (!empty($comment_errors)) {
				foreach ($comment_errors as $error) {
					echo "<div class='alert alert-danger text-center'>" . $error . '</div>' ;
				}
				// redirect
				$alert_danger1 = "please Read The Errors Above And try To fix Them";
				$alert_redirect_back = "active";
				$seconds = 3 ;
				redirectAlert();
			}
			/// if there wasn't any error */
			elseif (empty($comment_errors)) { 
			 	// insert data
			 	// get category id
			 	$stmt1 = $con->prepare("SELECT categories.ID FROM items INNER JOIN categories ON categories.ID = items.Cat_ID WHERE Item_ID = ? LIMIT 1");
			 	$stmt1->execute(array($item_id));
			 	$cat_id = $stmt1->fetchColumn();
			 	// insert the comment
			 	$stmt = $con->prepare("INSERT INTO comments (comment , comment_date , user_id , item_id , cat_id) VALUES  (? , NOW() , ? , ? , ?)");
			 	$stmt->execute(array($comment , $user_id , $item_id , $cat_id));
			 	$count = $stmt->rowCount();
			 	if ($count > 0) {
			 		$alert_success1 = "Congrats 1 comment added successfully";
			 		$seconds = 2 ;
			 		$alert_redirect_back = "active";
			 		redirectAlert();
			 	}elseif ($count = 0) {
			 		$alert_danger1 = "something went wrong please try again";
			 		$alert_redirect_back = "active";
			 		redirectAlert();
			 	}
			 } 


		}
		// if reques method wan't post
		else
		{
			$alert_danger1 = "You can't browse this page";
			$alert_redirect_home = "active";
			redirectAlert();
		} 
	} 
	// start Edit page
	elseif ($do == 'Edit') {
		// check id 
		$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;
		// get data 
		if ($comid > 0 ) {
			$stmt = $con->prepare(" SELECT * FROM comments WHERE c_id = $comid ");
			$stmt->execute();
			$comments = $stmt->fetchAll();
			$count = $stmt->rowCount();
			if ($count > 0) {
				foreach ($comments as $comment) {
				
?>
<h1 class="text-center">Edit Comment</h1>
	<div class="container">
		<?php $alert_warning1 = "All Fields With <span style='color:red;'>Red Stars</span> are Required";
		redirectAlert(); ?>
		<form class="form-horizontal" method="POST" action="?do=Update">
			<div class="row">
				<!-- Start User name and item's add in post -->
					<div class="form-group">
						<label class="control-label col-md-2">Item : </label>
						<div class="col-md-4">
							<!-- The hidden id -->
							<input type="hidden" name="comid" value="<?php echo $comment['c_id'] ?>">
							<select name="item_id" required="required">
								<option value="0">Choose The item you wanna add commen in.</option>
								<?php
									$stmt1 = $con->prepare("SELECT * FROM items");
									$stmt1->execute();
									$items = $stmt1->fetchAll();
									$count1 = $stmt1->rowCount();
									if ($count1 > 0) {
										foreach ($items as $item) {
											echo "<option value='".$item['Item_ID']."'";
												if ($item['Item_ID'] == $comment['item_id']) {
													echo "selected";
												};

												echo ">".$item['Name']."</option>";
										}
									}
								?>
							</select>
						</div>
						<label class="control-label col-md-2">UserName : </label>
						<div class="col-md-4">
							<select name="user_id" required="required">
								<option value="0">Choose User Name.</option>
								<?php
									$stmt2 = $con->prepare("SELECT * FROM users");
									$stmt2->execute();
									$users = $stmt2->fetchAll();
									$count2 = $stmt2->rowCount();
									if ($count2 > 0) {
										foreach ($users as $user) {
											echo "<option value='".$user['UserID']."'";
											if ($user['UserID'] == $comment['user_id']) {
												echo "selected";
											}
											echo ">".$user['Username']."</option>";
										}
									}
								?>
							</select>	
						</div>
					</div>
				<!-- End The items post and usernaem of the adder -->
				<!-- start comment text area -->
				<div class="form-group">
					<label class="control-label col-md-2">Comment : </label>
					<div class="col-md-10">
						<textarea name="comment" class="form-control" required="required" placeholder="Write Your Comment... " id="textarea_comment"><?php echo $comment['comment'] ?></textarea>
					</div>
				</div>
				<!-- End comment text area -->
				<!-- Start Submit -->
				<div class="form-group">
					<div class="col-md-4 col-md-push-4">
						<input type="submit" value="<?php echo lang('ADD') ?>" class="btn btn-primary btn-block">
					</div>
				</div>
				<!-- End Submit -->


			</div>
		</form>
	</div>
<?php 
				}
			}
		}else
		{
			$alert_danger1 = "There is no such member";
			$alert_redirect_home = "active";
			redirectAlert();
		}

	}
	// start Update 
	elseif ($do == "Update") {
		if ($_SERVER ['REQUEST_METHOD'] == "POST") {
			$id 	 = $_POST['comid'];
			$item_id = $_POST['item_id'];
			$user_id = $_POST['user_id'];
			$comment = $_POST['comment'];
			$stmt = $con->prepare(" 
				UPDATE comments SET comment = ? , user_id = ? , item_id = ? WHERE c_id = $id ");
			$stmt->execute(array($comment , $user_id , $item_id ));
			$count = $stmt->rowCount();
			if ($stmt && $count == 0) {
				$alert_success1 = "Every Thing is ok but nothing updated";
				$alert_redirect_back = "active";
				redirectAlert();
			}elseif ($count > 0 ) {
				$alert_success1 = "Comment Updated Successfully";
				$alert_redirect_back = "active";
				$seconds = 0.5;
				redirectAlert();			
			}else
			{
				$alert_danger1 = "something went wrong please try again";
				$alert_redirect_home = "active";
				redirectAlert();
			}
			
		}else
		{
			$alert_danger1 = "You can't Brwose this page";
			$alert_redirect_home = "active";
			redirectAlert();
		}
	}
// start Delete Page
	elseif ($do == 'Delete') {
		// check the id
		$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;
		// check if exist
		$check = checkItem('c_id' , 'comments' , $comid);
		if ($check > 0) {
			$stmt = $con->prepare("DELETE FROM comments WHERE c_id = ?");
			$stmt->execute(array($comid));
			$alert_success1 =  'Only '.$stmt->rowCount().' Item Deleted successfully ';
			$alert_redirect_back = "active";
			$seconds = 0.5;
			redirectAlert();
		}else
		{
			$alert_danger1 = "There is no such Comment";
			$alert_redirect_home = "active";
			redirectAlert();
		}
	}
	// start Approve comment page
	elseif ($do == 'approve') {
		$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;
		// check if exist
		$check = checkItem('c_id' , 'comments' , $comid);
		if ($check > 0 ) {
			$stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
			$stmt->execute(array($comid));
			$count = $stmt->rowCount();
			if ($count > 0) {
				$alert_success1 = "comment Aproved successfully " ; 
				$alert_redirect_back = "active";
				$seconds = 0.5;
				redirectAlert();
			}else
			{
				$alert_warning1 = "Comment Allready approved so can't approve again";
				$alert_redirect_home = "active";
				redirectAlert();
			}
		}else
		{
			$alert_danger1 = "There is no such Comment or it been just approved";
			$alert_redirect_home = "active";
			redirectAlert();
		}
	}
	// start Disapprove comment page
	elseif ($do == 'disapprove') {
		$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;
		// check if exist
		$check = checkItem('c_id' , 'comments' , $comid);
		if ($check > 0 ) {
			$stmt = $con->prepare("UPDATE comments SET status = 0 WHERE c_id = ?");
			$stmt->execute(array($comid));
			$count = $stmt->rowCount();			
			if ($count > 0) {
				$alert_success1 = "comment Disaproved successfully " ; 
				$alert_redirect_back = "active";
				$seconds = 0.5;
				redirectAlert();
			}else
			{
				$alert_warning1 = "Comment Allready approved so can't Disapprove again";
				$alert_redirect_home = "active";
				redirectAlert();
			}
		}else
		{
			$alert_danger1 = "There is no such Comment or it been just Disapproved";
			$alert_redirect_home = "active";
			redirectAlert();
		}
	}
		include $tpl.'footer.php';	// the footer
}else
{
	header('location: ../index.php'); 
}
 	ob_end_flush(); 
?>