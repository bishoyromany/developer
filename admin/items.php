<?php
	ob_start();
	/*
	((((((((((((((((((Catagories page))))))))))))))))))))))
	**** Manage Members
	**** EDIT members
	***************************************************	
	*/
	session_start();
	if (isset($_SESSION['Username'])){
	include 'includes/languages/english.php';	// languages 
	$pageTitle =lang('ITEMST') ; //title
	include 'init.php';	// the inip page
	}
 	else {header('location: index.php');	// if the method wasn't post
 	};
// check if user or admin
if (proveAdmin() == 1)
{
	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
	// start items
	if ($do == 'Manage') {
		$stmt = $con->prepare("
			SELECT 
				items.*, 
				categories.Name AS Cat_Name ,
				users.Username 
			FROM 
				items
			INNER JOIN 
				categories 
			ON 
				categories.ID = items.Cat_ID
			INNEr JOIN 
				users 
			ON 
				users.UserID = items.Member_ID
			ORDER BY Item_ID DESC
		");
		$stmt->execute();
		$items = $stmt->fetchAll();
		if (! empty($items)) {
			
?>
	<h1 class="text-center">Manage Items</h1>
	<div class="container items">
		<div class="table-responsive">
			<table class="main-table text-center table table-bordered">
				<tr>
					<td>#ID</td>
					<td>Name</td>
					<td>Description</td>
					<td>Price</td>
					<td>Date</td>
					<td>User</td>
					<td>Category</td>
					<td>Control</td>
				</tr>
				<?php
					foreach ($items as $item) {
					echo "<tr>";
						echo "<td>".$item['Item_ID']."</td>";
						echo "<td>".$item['Name']."</td>";
						echo "<td class='show_description'><div class='btn btn-success'>Show</div>
						</td>";
						echo "<td class='hide_description' style='width:15%;'>".$item['Description']."</td>";
						echo "<td>$".$item['Price']."</td>";
						echo "<td>".$item['Add_Date']."</td>";
						echo "<td>".$item['Username']."</td>";
						echo "<td>".$item['Cat_Name']."</td>";
						echo '<td>';
							echo '<a href="items.php?do=Edit&itemID='.$item['Item_ID'].'" class="btn btn-success hvr-icon-pulse-grow"><i class="fa fa-edit hvr-icon"></i> '.lang('EDIT').'</a> ';
							echo '<a href="items.php?do=Delete&itemID='.$item['Item_ID'].'" class="btn btn-danger confirm hvr-icon-pulse-grow"><i class="fa fa-close hvr-icon"></i> '.lang('DELETE').' </a> ';
							if ($item['approve'] == 0) {
								echo " <a href='items.php?do=approve&itemID=".$item['Item_ID']."' class='btn btn-info hvr-icon-pulse-grow'>
									<i class='fa fa-check hvr-icon'></i>
									Approve</a>";	
							}elseif ($item['approve'] == 1) {
								echo " <a href='items.php?do=disapprove&itemID=".$item['Item_ID']."' class='btn btn-primary hvr-icon-pulse-grow' style='font-size:12px;'>
									<i class='fa fa-close hvr-icon'></i>
									disapprove</a>";
							}

						echo "</td>";
					echo "</tr>";
					}
				?>
			</table>
		</div>
		<a class="btn btn-primary" href="?do=Add">Add New Item</a>
	</div>
<?php
		}else
		{
?>	
	<h1 class="text-center">Manage Items empty :(</h1>
	<div class="container">
		<div class="nice_message">No Itemes To Show</div>
		<a class="btn btn-primary" href="?do=Add">Add New Item</a>
	</div>
<?php
		}


	}
	// end items


// start Add form item
	elseif ($do == 'Add') {
?>
	
		<h1 class="text-center"> Add New Item </h1>
		<?php 
			$alert_warning1 = "All Fields with <span style='color:red;'> red </span>star are Required";
			redirectAlert();
		 ?>
		<div class="container">
			<form class="form-horizontal" method="POST" action="?do=Insert">
				<div class="row">
					<div class="form-group">
						<!-- Input Item Name -->
						<label class="control-label col-md-2"><?php echo lang('NAME') ?> : </label>
						<div class="col-md-4">
						<input type="text" name="Name" class="form-control" required="required"  placeholder="Write Item Name ..." style="height: 35px;max-height: 300px; max-width: 100%;">
						</div>						
						<!-- start Item Status good or old -->
						<label class="control-label col-md-2">Item Status : </label>
						<div class="col-md-4">
							<select name="Status" required="required" style="width: 100%;">
								<option value="0">Select one of status under</option>
								<option value="1">New</option>
								<option value="2">Like New</option>
								<option value="3">Used</option>
								<option value="4">Old</option>
							</select>
						</div>
						<!-- Input Item Description -->
					</div>
					<div class="form-group">
						<label class="control-label col-md-2"><?php echo lang('DESCRIPTION') ?> : </label>
						<div class="col-md-10">
							<textarea class="form-control hidden" required="required"  placeholder="Descripe The Item ..." name="Description"  style="height: 35px;max-height: 300px;" id="real_comment"></textarea>
							<textarea class="form-control fake_comment" required="required"  placeholder="Descripe The Item ..." style="height: 300px;max-height: 1000px; max-width: 100%;"></textarea>
						</div>
					</div>
					<div class="form-group">
						<!-- Start Price Field -->
						<label class="control-label col-md-2">Price : $</label>
						<div class="col-md-4">
							<input type="test" name="Price" required="required"  placeholder="Item Price ..." class="form-control" title="The items price equal in dollars">
						</div>	
						<!-- Start Made In Country Field -->	
						<label class="control-label col-md-2">Made In : </label>
						<div class="col-md-4">
							<input type="text" name="Country_Made"  class="form-control" required="required"  placeholder="example .. England">
						</div>
					</div>
					<div class="form-group">
						<!-- Start add Tags input field -->
						<label class="col-md-2 control-label">Tags : </label>
						<div class="col-md-4">
							<input type="text" name="tags" class="form-control" placeholder="Use (,) between each tag phones,iphone,apple,cheap...">
						</div>
						<!-- Start Quantity option -->
						<label class="control-label col-md-2">Quantity : </label>
						<div class="col-md-4">
							<input type="number" class="form-control" name="Quantity" required="required" placeholder="Home many Items do you have .. ">
						</div>
					</div>
					<!-- Start Choose user option -->
					<div class="form-group">
						<label class="col-md-2 control-label">Member : </label>
						<div class="col-md-4">
							<select required="required" name="Member" style="width: 100%;">
								<option value="0">Select The Seller Of The Categories</option>
								<?php 
									$stmt = $con->prepare("SELECT * FROM users");
									$stmt->execute();
									$users = $stmt->fetchAll();
									foreach ($users as $user) {
										echo "<option value='".$user['UserID'] ."'>".$user['Username'] ."</option>";
									}
								 ?>
							</select>
						</div>
						<!-- Start Category select box -->
						<label class="col-md-2 control-label">Category :</label>
						<div class="col-md-4">
							<select required="required" name="category" style="width: 100%;">
								<option value="0">Select The Seller Of The Item</option>
								<?php 
									$cats = getDAta("*" , "categories" , "WHERE parent =?" , 0 , "Ordring" , "ASC" , "");
									foreach ($cats as $cat) {
										echo "<option value='".$cat['ID'] ."'>".$cat['Name'] ."</option>";
									$soncats = getData("*" , "categories" , "WHERE parent = ?" , $cat['ID'] , "Ordring" , "ASC" , "");
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
						<!-- Input submit -->
						<div class="col-md-4 col-md-push-4">
							<input type="submit" value="<?php echo lang('ADD') ?>" class="btn btn-primary btn-block">
						</div>
					</div>
				</form>
		</div>
<?php 	
	}
// end add items



// start insert items
	elseif ($do == 'Insert') {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// get data
			$Name 			= $_POST['Name'];
			$Description 	= $_POST['Description'];
			$Price 			= $_POST['Price'];
			$Country_Made 	= $_POST['Country_Made'];
			$Status 		= $_POST['Status'];
			$Quantity 		= $_POST['Quantity'];
			$category 		= $_POST['category'];
			$Member 		= $_POST['Member'];
			$tags 			= $_POST['tags'];
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
			if (empty($_POST['Price'])) {
				$Items_errors[] = "You Should't leave Item price empty";
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
			// check the member and category select box
			if ($_POST['Member'] == 0) {
				$Items_errors[] = "please you sould choose a member";
				 
			}
			if ($_POST['category'] == 0) {
				$Items_errors[] = "please you sould choose a category";
				 
			}
			// echo errors if there sere errors
			foreach ($Items_errors as $error) {
				echo '<div class="alert alert-danger text-center">'.$error. '</div>';
			}	
			if (!empty($Items_errors)) {
				$alert_danger1 = "Place read the errors above";
				$alert_redirect_back = "back";
				$seconds = 5;
				redirectAlert();
			}
			// if there's no error insert data
			elseif (empty($Items_errors)) {
				$stmt2 = $con->prepare("INSERT INTO items ( Name , Description , Add_Date , Price , Country_Made , Status , Quantity , Cat_ID , Member_ID , tags ) VALUES (:Name , :Description , now() ,:Price ,:Country_Made , :Status , :Quantity , :categoryid , :memberid , :tags )");
				$stmt2->execute(array(
					':Name' 		=>	$Name ,
					':Description' 	=>	$Description ,
					':Price' 		=>	$Price ,
					':Country_Made' =>	$Country_Made ,
					':Status'  		=>	$Status ,
					':Quantity' 	=>	$Quantity,
					':categoryid'	=> 	$category,
					':memberid'		=> 	$Member,
					':tags'			=> 	$tags
				));
				$count = $stmt2->rowCount();
				if ($count > 0) {
					$alert_success1 = "Item Added Successfully Congratulations";
					$alert_redirect_back = "active";
					$seconds = "2";
					redirectAlert();
				}elseif ($count = 0) {
					$alert_danger1 = "SomeThing Went Wrong";
					$alert_redirect_home = "active";
					redirectAlert();
				}else
				{
					$alert_danger1 = "SomeThing Went Wrong";
					$alert_redirect_home = "active";
					redirectAlert();
				}
			}
		}else
		{
			$alert_redirect_home = "active";
			redirectAlert();
		}
	}
// end insert items


// start Edit
	elseif ($do == 'Edit') {
		
		$itemID =  isset($_GET['itemID']) && is_numeric($_GET['itemID']) ? intval($_GET['itemID']) : 0 ;
		// get the data matches item ID
		$stmt = $con->prepare("SELECT items.* , categories.Name AS Cat_Name , users.Username FROM items INNER JOIN categories ON categories.ID = items.Cat_ID INNER JOIN users ON users.UserID = items.Member_ID WHERE item_ID = $itemID ");
		$stmt->execute();
		// fetch data
		$itemsE = $stmt->fetchAll();
		// check if data exists
		$count = $stmt->rowCount();
		// if data exists do this
		if ($count > 0) {
			foreach ($itemsE as $items) {
			
?>
	<h1 class="text-center">Edit Item</h1>
	<div class="container">
		<form class="form-horizontal" action="?do=Update" method="POST">
			<div class="row">
				<div class="form-group">
					<!-- <hidden id -->
						<input type="text" hidden name="Item_ID" value="<?php echo $items['Item_ID']; ?>">
					<!-- Input Item Name -->
					<label class="control-label col-md-2"><?php echo lang('NAME') ?> : </label>
					<div class="col-md-4">
					<input type="text" name="Name" class="form-control" required="required"  placeholder="Write Item Name ..." style="height: 35px;max-height: 300px; max-width: 100%;" value="<?php echo $items['Name']; ?>">
					</div>
					<!-- Item Status input field -->
					<label class="control-label col-md-2">Status : </label>
					<div class="col-md-4">
						<select name="Status">
							<option value="0">The item status Selected Auto </option>
							<option value="1" <?php if ($items['Status'] == 1) {
								echo "selected";
							} ?>>New</option>
							<option value="2" <?php if ($items['Status'] == 2) {
								echo "selected";
							} ?>>Like New</option>
							<option value="3" <?php if ($items['Status'] == 3) {
								echo "selected";
							} ?>>Used</option>
							<option value="4" <?php if ($items['Status'] == 4) {
								echo "selected";
							} ?>>Old</option>
						</select>
					</div>
					<!-- Input Item Description -->
				</div>
				<div class="form-group">
					<label class="control-label col-md-2"><?php echo lang('DESCRIPTION') ?> : </label>
					<div class="col-md-10">
						<textarea class="form-control hidden" required="required"  placeholder="Descripe The Item ..." name="Description"  style="height: 35px;max-height: 300px;" id="real_comment"><?php echo $items['Description']; ?></textarea>
						<textarea class="form-control fake_comment" required="required"  placeholder="Descripe The Item ..." style="height: 300px;max-height: 1000px; max-width: 100%;"><?php echo $items['Description']; ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<!-- Price input field -->
					<label class="control-label col-md-2">Price$ : </label>
					<div class="col-md-4">
						<input type="text" name="Price" class="form-control" required="required" value="<?php echo $items['Price'] ?>">
					</div>
					<!-- Country Made -->
					<label class="control-label col-md-2">Country Made : </label>
					<div class="col-md-4">
						<input type="text" name="Country_Made" class="form-control" required="required" value=" <?php echo $items['Country_Made'] ?> "/>
					</div>
				</div>
				<div class="form-group">
					<!-- Start add Tags input field -->
					<label class="col-md-2 control-label">Tags : </label>
					<div class="col-md-4">
						<input value="<?php echo $items['tags']; ?>" type="text" name="tags" class="form-control" placeholder="Use (,) between each tag phones,iphone,apple,cheap...">
					</div>
					<!--  Quantity -->
					<label class="control-label col-md-2">Quantity : </label>
					<div class="col-md-4">
						<input type="number" name="Quantity" class="form-control" required="required"value="<?php echo $items['Quantity'] ?>"/>
					</div>
				</div>
				<div class="form-group">
				<!-- The user Who Added Item -->
					<label class="control-label col-md-2">UserName : </label>
					<div class="col-md-4">
						<select name="Member">
							<option value="0">The user who added item selected auto </option>
							<?php 
								$stmt2 = $con->prepare("SELECT * FROM users");
								$stmt2 ->execute();
								$users = $stmt2->fetchAll();
								foreach ($users as $user) {
							?>
								<option value="<?php echo $user['UserID'] ?>"<?php if ($user['UserID'] == $items['Member_ID']) {
									echo "selected";
								} ?>><?php echo $user['Username']; ?></option>
							<?php		
								}
							?>
						</select>
					</div>
				<!-- The Category of the  Item -->
					<label class="control-label col-md-2">Category : </label>
					<div class="col-md-4">
						<select name="category">
							<option value="0">The Item Category Selected Auto </option>
							<?php 
								$stmt3 = $con->prepare("SELECT * FROM categories");
								$stmt3 ->execute();
								$cats = $stmt3->fetchAll();
								foreach ($cats as $cat) {
							?>
								<option value="<?php echo $cat['ID'] ?>"<?php if ($cat['ID'] == $items['Cat_ID']) {
									echo "selected";
								} ?>><?php echo $cat['Name']; ?></option>
							<?php		
								}
							?>
						</select>
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
			}
		}else // if user not exists
		{
			$alert_danger1 = "There is No such Item";
			$alert_redirect_home = 'home';
			$seconds = 5;
			redirectAlert();
		}
	}
// End Edit


// start Update
	elseif ($do == 'Update') {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// get data
			$item_ID 		= $_POST['Item_ID'];
			$Name 			= $_POST['Name'];
			$Description 	= $_POST['Description'];
			$Price 			= $_POST['Price'];
			$Country_Made 	= $_POST['Country_Made'];
			$Status 		= $_POST['Status'];
			$Quantity 		= $_POST['Quantity'];
			$category 		= $_POST['category'];
			$Member 		= $_POST['Member'];
			$tags 			= $_POST['tags'];
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
			if (empty($_POST['Price'])) {
				$Items_errors[] = "You Should't leave Item price empty";
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
			// check the member and category select box
			if ($_POST['Member'] == 0) {
				$Items_errors[] = "please you sould choose a member";
				 
			}
			if ($_POST['category'] == 0) {
				$Items_errors[] = "please you sould choose a category";
				 
			}
			// echo errors if there sere errors
			foreach ($Items_errors as $error) {
				echo '<div class="alert alert-danger text-center">'.$error. '</div>';
			}	
			if (!empty($Items_errors)) {
				$alert_danger1 = "Place read the errors above";
				$alert_redirect_back = "back";
				$seconds = 5;
				redirectAlert();
			}
			// if there was no errors
			elseif (empty($Items_errors)) {
				$stmt = $con->prepare("
					UPDATE 
						items
					SET
						Name = ? ,
						Description = ? ,
						Price = ? , 
						Country_Made = ? , 
						Status = ? ,
						Quantity = ? ,
						Cat_ID = ? ,
						Member_ID = ?,
						tags = ?
					WHERE
						item_ID = $item_ID 
					");
				$stmt->execute(array(
					$Name , $Description , $Price , $Country_Made , $Status , $Quantity , $category , $Member , $tags
				));
				$count = $stmt->rowCount();
				if ($count > 0) {
					$alert_success1 = "Item Updated Successfully";
					$alert_redirect_back = "active";
					$seconds = 2;
					redirectAlert();
				}else
				{
					$alert_warning1 = "Something Went wrong please Try Again";
					$alert_redirect_back = "active";
					redirectAlert();
				}
			}
		}else
		{
			$alert_danger1 = "You can't Browse This page";
			$alert_redirect_home = 'home';
			$seconds = 0;
			redirectAlert();
		}
	}
// End Update


// start Delete Page
	elseif ($do == 'Delete') {
		// check the id
		$itemID = isset($_GET['itemID']) && is_numeric($_GET['itemID']) ? intval($_GET['itemID']) : 0 ;
		// check if exist
		$check = checkItem('item_ID' , 'items' , $itemID);
		if ($check > 0) {
			$stmt = $con->prepare("DELETE FROM items WHERE item_ID = ?");
			$stmt->execute(array($itemID));
			$alert_success1 =  'Only '.$stmt->rowCount().' Item Deleted successfully ';
			$alert_redirect_back = "active";
			$seconds = 0.5;
			redirectAlert();
		}else
		{
			$alert_danger1 = "There is no such Item";
			$alert_redirect_home = "active";
			redirectAlert();
		}
	}
// End Delete Page
// start aprove page
	elseif ($do == 'approve') {
		// check the id
		$itemID = isset($_GET['itemID']) && is_numeric($_GET['itemID']) ? intval($_GET['itemID']) : 0 ;
		// check if exist
		$check = checkItem('item_ID' , 'items' , $itemID);
		if ($check > 0) {
			$stmt = $con->prepare("UPDATE items SET approve = 1 WHERE item_ID = ?");
			$stmt->execute(array($itemID));
			if ($stmt->rowCount() > 0) {
				$alert_success1 = "Item approved succefully";
				$alert_redirect_back = "active";
				$seconds = 0.5;
				redirectAlert();
			}else
			{
				$alert_danger1 = "Sorry Item dosen't exist or Allready approved";
				alert_redirect_home();
			}
		}

	}
// End approve page

// start deaprove page
	elseif ($do == 'disapprove') {
		// check the id
		$itemID = isset($_GET['itemID']) && is_numeric($_GET['itemID']) ? intval($_GET['itemID']) : 0 ;
		// check if exist
		$check = checkItem('item_ID' , 'items' , $itemID);
		if ($check > 0) {
			$stmt = $con->prepare("UPDATE items SET approve = 0 WHERE item_ID = ?");
			$stmt->execute(array($itemID));
			if ($stmt->rowCount() > 0) {
				$alert_success1 = "Item disapproved succefully";
				$alert_redirect_back = "active";
				$seconds = 0.5;
				redirectAlert();
			}else
			{
				$alert_danger1 = "Sorry Item dosen't exist or Allready deapproved";
				alert_redirect_home();
			}
		}

	}
// End deapprove page

	include $tpl.'footer.php';	// the footer
}else {header('location: ../index.php');	// if the method wasn't post
};
 	ob_end_flush(); 
?>