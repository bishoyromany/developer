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
	$pageTitle =lang('CATEGORIES') ; //title
	include 'init.php';	// the inip page
	}
 	else {header('location: index.php');	// if the method wasn't post
 	};
// check if admin or user
if (proveAdmin() == 1)
{
	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';


	// start Categories
	if ($do == 'Manage') {
		$sort = 'ASC';
		$sort_array =array('ASC' , 'DESC');
		if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
			$sort = $_GET['sort'];
		}
		$cats = getData("*" , "categories" , "WHERE parent = 0" , "" , "Ordring" , "$sort" , "" , "");
		if (! empty($cats)) {
?>
<h1 class="text-center">Manage Categories</h1>
<div class="container categories">
	<div class="panel panel-default">
		<div class="panel-heading">
			<i class="fa fa-edit"></i> Manage Categories
			<div class="option pull-right">
				<i class="fa fa-sort"></i> Ordring [
				<a class="<?php if($sort == 'ASC'){ echo 'active'; }; ?>" href="?sort=ASC"> Asc</a> | 
				<a class="<?php if($sort == 'DESC'){ echo 'active'; }; ?>" href="?sort=DESC">Desc </a>]

				<i class="fa fa-eye"></i> View : [
				<span class="active" data-view='classic'>Classic</span> | 
				<span data-view='full'>Full</span> ]
 			</div>
		</div>
		<div class="panel-body">
<?php
	foreach ($cats as $cat) {
		echo "<div class='cat'>";
			echo "<div class='hidden-buttons'>";
					echo "<a class='btn btn-xs btn-primary hvr-icon-pulse-grow' href='categories.php?do=Edit&catID=".$cat['ID']."'><i class='fa fa-edit hvr-icon'></i> Edit</a>";
					echo "<a class='btn btn-xs btn-danger hvr-icon-pulse-grow confirm' href='categories.php?do=Delete&catID=".$cat['ID']."'><i class='fa fa-close hvr-icon'></i> Delete</a>";
			echo "</div>";
				echo "<h3><span class='the_folder_open_notopen'><i class='fa fa-folder'></i></span> ". $cat['Name']."</h3>";
			echo "<div class='full-view'>";

				echo "<p>"; if(empty($cat['Description']))
				{
					echo "<span style='color:red;'>This Category has no Description</span>";
				}elseif (!empty($cat['Description'])) {
					echo $cat['Description'];
				} 
				echo "</p>";
				echo "<span class='Ordring' title='the category order in the control panel you can change the number'><i class='fa fa-adjust'></i> Order Number : ". $cat['Ordring'].'</span>';
				if ($cat['Visibility'] == 1) {
					echo "<span title='hidden Category' class='Visibility hvr-icon-grow'><i class='fa fa-eye-slash hvr-icon'></i> Category is hidden</span>";
				};
				if ($cat['Allow_Comment'] == 1) {
					echo "<span class='Comments hvr-icon-grow'><i class='fa fa-comment hvr-icon'></i> Comments disabled</span>";
				};
				if ($cat['Allow_Ads'] == 1) {
					echo "<span class='Adsdesabled hvr-icon-grow'><i class='fa fa-close  hvr-icon'></i> Ads disabled</span>";
				};
				$childecats = getData("*" , "categories" , "WHERE parent = {$cat['ID']}" , "" , "ID" , "ASC" , "");
				if (!empty($childecats)): ?>
					<h3 class='subcats_h3_categories'>Sub Categories</h3>
				<?php
				foreach ($childecats as $c):
				?>
					<p class="h4 subcats_container"><a href='categories.php?do=Edit&catID=<?php echo $c['ID'] ?>'><?php echo $c['Name']; ?></a><a class='delete_cat' href='categories.php?do=Delete&catID=<?php echo $c['ID'] ?>'> Delete</a></p>
					
					
				<?php
				endforeach;

				elseif (empty($childecats)):
					echo "<br><br>";
					echo "<h3>No subcats</h3>";
				endif;
			echo "</div>";
		echo "</div>";
		echo "<hr>";
	}
?>
		</div>
	</div>
	<a href="categories.php?do=Add" class="btn btn-primary btn-md add-button"><i class="fa fa-plus"></i> Add New Category</a>
</div>
<?php
		}else
		{
?>	<h1 class="text-center">Manage categories empty :(</h1>
	<div class="container">
		<div class="nice_message">No categories To Show</div>
		<a class="btn btn-primary" href="?do=Add">Add New category</a>
	</div>
<?php
		}
	}
	// end Categories





// start Add form Categoriess
	elseif ($do == 'Add') {
?>
		<div class="container">
			<h1 class="text-center"> Add New Category </h1>
			<?php 
				$alert_warning1 = "All Fields with <span style='color:red;'> red </span>star are Required";
				redirectAlert();
			 ?>
			<form class="form-horizontal" method="POST" action="categories.php?do=Insert">
				<div class="row">
					<div class="form-group">
						<!-- Input Cat Name -->
						<label class="control-label col-md-2"><?php echo lang('NAME') ?> : </label>
						<div class="col-md-6">
						<input type="text" name="Name" class="form-control" required="required" placeholder="Write Category Name ...">
						</div>
						<?php
							$cats = getData ("*" , "categories" , "WHERE parent = 0" , "" , "ID" , "DESC" , "" , "");
						?>
						<label class="control-label col-md-2">Parent? : </label>
						<select name="parent">
							<option value="0">None.</option>
							<?php foreach ($cats as $cat): ?>
							<option value="<?php echo $cat['ID']; ?>"><?php echo $cat['Name']; ?></option>
						<?php endforeach; ?>
						</select>
					</div>
						<!-- Input Cat Description -->
					<div class="form-group">
						<label class="control-label col-md-2"><?php echo lang('DESCRIPTION') ?> : </label>
						<div class="col-md-6">
							<input class="form-control" placeholder="Descripe The Category ..." name="Description">
						</div>
						<!-- Input Order -->
						<label class="control-label col-md-2"><?php echo lang('ORDRING') ?> : </label>
						<div class="col-md-2">
							<input type="number" name="Ordring" class="form-control"  placeholder="Write number...">
						</div>
					</div>
					<!-- Start Options -->
					<div class="form-group">

						<!-- Input Visibility -->
						<label class="control-label col-md-2"><?php echo lang('VISIBILITY') ?> : </label>
						<div class="col-md-2">
							<div>
								<input type="radio" name="Visibility" value="0" id="visibl_yes" checked>
								<label for="visibl_yes">Show</label>
							</div>
							<div>	
								<input type="radio" name="Visibility" value="1" id="visibl_no" >
								<label for="visibl_no">Hide</label>
							</div>
						</div>
						<!-- Input Allow_Comments -->
						<label class="control-label col-md-2"><?php echo lang('ALLOW_COMMENTS') ?>: </label>
						<div class="col-md-2">
							<div>
								<input type="radio" name="Allow_Comment" value="0" id="comment_yes" checked>
								<label for="comment_yes">Yes</label>
							</div>
							<div>	
								<input type="radio" name="Allow_Comment" value="1" id="comment_no" >
								<label for="comment_no">No</label>
							</div>
						</div>
						<!-- Input Allow_Ads -->
						<label class="control-label col-md-2"><?php echo lang('ALLOW_ADS') ?> : </label>
						<div class="col-md-2">
							<div>
								<input type="radio" name="Allow_Ads" value="0" id="ads_yes" checked>
								<label for="ads_yes">Yes</label>
							</div>
							<div>	
								<input type="radio" name="Allow_Ads" value="1" id="ads_no" >
								<label for="ads_no">No</label>
							</div>
						</div>
					</div>
					<!-- Start Options -->
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
	// end Add Category
// Insert Categorty
	elseif($do == 'Insert')
	{ echo '<div class="container">';
		// check the method
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// get the input fields values
			$Name 			= $_POST['Name'];
			$parent 		= $_POST['parent'];
			$Descripe 		= $_POST['Description'];
			$Ordring 		= $_POST['Ordring'];
			$Visibility 	= $_POST['Visibility'];
			$Allow_Comment 	= $_POST['Allow_Comment'];
			$Allow_Ads 		= $_POST['Allow_Ads'];
			// check if name empty or not
			if (empty($Name)) {
				$alert_danger1 = "Don't Leave Category Name empty";
				$alert_redirect_back = "active";
				redirectAlert();
			}elseif(!empty($Name))
			{
				// check if name exist
				$check = checkItem('Name', 'categories' , $Name);
				if ($check > 0) {
					$alert_danger1 = "Category Allready Exists" ;
					$alert_redirect_back = "active";
					redirectAlert();
				}else
				{
					$stmt2 = $con->prepare("INSERT INTO categories ( Name , Description , parent , Ordring , Visibility , Allow_Comment , Allow_Ads ) VALUES ( :Name ,:Description , :parent ,:Ordring ,:Visibility ,:Allow_Comment ,:Allow_Ads ) ");
					$stmt2->execute(array(
						 ':Name' 			=> $Name,
						 ':Description' 	=> $Descripe,
						 ':parent' 			=> $parent,
						 ':Ordring' 		=> $Ordring,
						 ':Visibility' 		=> $Visibility,
						 ':Allow_Comment' 	=> $Allow_Comment,
						 ':Allow_Ads' 		=> $Allow_Ads
					));
					if ($stmt2) {
						$alert_success1 = "Data Inserted Successfully";
						$alert_redirect_back = "active";
						$seconds = "0.5";
						redirectAlert();
					}else
					{
						$alert_redirect_home = "active";
						redirectAlert();
					}
				}
			}
		}
		echo "</div>";
	}
// End Insert Category



		// start edit
	
	elseif ($do == 'Edit') 
	{

		// checking the get request id
		$catID =isset($_GET['catID']) && is_numeric($_GET['catID']) ? intval($_GET['catID']) : 0;
		// getting data from data base
		$stmt=$con->prepare("SELECT * FROM categories WHERE ID = ?");
		$stmt->execute(array($catID));
		$cat = $stmt->fetch();
		$count = $stmt->rowCount(); 
		// check if user exists
		if ($count > 0)
		{
		// create the form
?>
			<div class="container">
				<h1 class="text-center"> Edit Category </h1>
			<?php 
				$alert_warning1 = "All Fields with <span style='color:red;'> red </span>star are Required";
				redirectAlert();
			 ?>
			<form class="form-horizontal" method="POST" action="categories.php?do=Update">
				<div class="row">
					<!-- Hidden ID -->
					<input type="hidden" name="id" value="<?php echo $cat['ID'] ?>">
					<div class="form-group">
						<!-- Input Cat Name -->
						<label class="control-label col-md-2"><?php echo lang('NAME') ?> : </label>
						<div class="col-md-6">
						<input type="text" name="Name" class="form-control" placeholder="Write Category Name ..." value="<?php echo $cat['Name'] ?>" required="required">
						</div>
						<!-- Select if parent or not -->
						<?php
						$catme = getData ("*" , "categories" , "WHERE parent = 0 AND ID != {$cat['ID']}" , "" , "ID" , "DESC" , "" , "");
						?>

						<label class="control-label col-md-2">Parent? : </label>
						<select name="parent">
							<option value="0">None.</option>
							<?php foreach ($catme as $c): 
								?>
							<option value="<?php echo $c['ID']; ?>" <?php 
								if ($cat['parent'] == $c['ID'] ): echo "selected"; endif;
							 ?>><?php echo $c['Name']; ?></option>
						<?php endforeach; ?>
						</select>
						</div>

						<!-- Input Cat Description -->
					<div class="form-group">
						<label class="control-label col-md-2"><?php echo lang('DESCRIPTION') ?> : </label>
						<div class="col-md-6">
							<input class="form-control" placeholder="Descripe The Category ..." name="Description" value="<?php echo $cat['Description'] ?>">
						</div>
						<!-- Input Order -->
						<label class="control-label col-md-2"><?php echo lang('ORDRING') ?> : </label>
						<div class="col-md-2">
							<input type="number" name="Ordring" class="form-control"  placeholder="Write number..." value="<?php echo $cat['Ordring'] ?>">
						</div>
					</div>
					<!-- Start Options -->
					<div class="form-group">

						<!-- Input Visibility -->
						<label class="control-label col-md-2"><?php echo lang('VISIBILITY') ?> : </label>
						<div class="col-md-2">
							<div>
								<input type="radio" name="Visibility" value="0" id="visibl_yes" <?php if ($cat['Visibility'] == 0) {
									echo 'checked';
								} ?> >
								<label for="visibl_yes">Show </label>
							</div>
							<div>	
								<input type="radio" name="Visibility" value="1" id="visibl_no" <?php if ($cat['Visibility'] == 1) {
									echo 'checked';
								} ?>>
								<label for="visibl_no">Hide</label>
							</div>
						</div>
						<!-- Input Allow_Comments -->
						<label class="control-label col-md-2"><?php echo lang('ALLOW_COMMENTS') ?>: </label>
						<div class="col-md-2">
							<div>
								<input type="radio" name="Allow_Comment" value="0" id="comment_yes" <?php if ($cat['Allow_Comment'] == 0) {
									echo 'checked';
								} ?>>
								<label for="comment_yes">Yes</label>
							</div>
							<div>	
								<input type="radio" name="Allow_Comment" value="1" id="comment_no" <?php if ($cat['Allow_Comment'] == 1) {
									echo 'checked';
								} ?>>
								<label for="comment_no">No</label>
							</div>
						</div>
						<!-- Input Allow_Ads -->
						<label class="control-label col-md-2"><?php echo lang('ALLOW_ADS') ?> : </label>
						<div class="col-md-2">
							<div>
								<input type="radio" name="Allow_Ads" value="0" id="ads_yes" <?php if ($cat['Allow_Ads'] == 0) {
									echo 'checked';
								} ?>>
								<label for="ads_yes">Yes</label>
							</div>
							<div>	
								<input type="radio" name="Allow_Ads" value="1" id="ads_no" <?php if ($cat['Allow_Ads'] == 1) {
									echo 'checked';
								} ?>>
								<label for="ads_no">No</label>
							</div>
						</div>
					</div>
					<!-- start submit -->
					<div class="form-group">
						<!-- Input submit -->
						<div class="col-md-4 col-md-push-4">
							<input type="submit" value="<?php echo lang('UPDATE') ?>" class="btn btn-primary btn-block">
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
// start upday Category
elseif ($do == 'Update') {
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// get the data
		$ID 			= $_POST['id'];
		$Name 			= $_POST['Name'];
		$parent 		= $_POST['parent'];
		$Descripe 		= $_POST['Description'];
		$Ordring 		= $_POST['Ordring'];
		$Visibility 	= $_POST['Visibility'];
		$Allow_Comment 	= $_POST['Allow_Comment'];
		$Allow_Ads 		= $_POST['Allow_Ads'];
		// check name if exists
		if (empty($Name)) {
			$alert_warning1 = "You can't leave Category name empty";
		}elseif (!empty($Name)) {
			$stmt2 = $con->prepare("UPDATE categories SET
			 Name = ? , Description = ? , parent = ? , Ordring = ? , Visibility = ? , Allow_Comment = ? , Allow_Ads = ?   
			 WHERE ID = ?");
			$stmt2->execute(array($Name , $Descripe , $parent , $Ordring , $Visibility , $Allow_Comment , $Allow_Ads , $ID));
			$count = $stmt2->rowCount();
			if ($count > 0) {
				$alert_success1 = "Category Updated succefully";
				$seconds = "0.5";
				$alert_redirect_back = "active";
				redirectAlert();
			}elseif ($stmt2 && $count == 0) {
				$alert_success1 = "Category Updated succefully But Nothing Changed";
				$seconds = "2";
				$alert_redirect_back = "active";
				redirectAlert();
			}else
			{
				$alert_warning1 = "Nothing Updated Something went wrong";
				$seconds = "2";
				$alert_redirect_back = "active";
				redirectAlert();
			}
		}
	}else
	{
		$alert_danger1 = "You can't Browse This page Directly";
		$alert_redirect_home = "active";
		redirectAlert();
	}
}

// end Update Category


// Delete Category page
	elseif ($do == 'Delete') 
	{

		// checking the get request id
		$catID =isset($_GET['catID']) && is_numeric($_GET['catID']) ? intval($_GET['catID']) : 0;
		// getting data from data base depend this id
		$check = checkItem('ID' , 'categories' , $catID);
		// check if user exists
		if ($check > 0) {
			$stmt2=$con->prepare("DELETE FROM categories WHERE ID = :catid");
			$stmt2->bindParam(':catid', $catID);
			$stmt2->execute();
			$alert_success1 =  'Only '.$stmt2->rowCount().' Member Deleted successfully ';
			$alert_redirect_back = "active";
			$seconds = 0.5;
			redirectAlert();

		}else
			{
				$alert_redirect_back = "active";
				redirectAlert();
			}
	}
// End Delete Category


	include $tpl.'footer.php';	// the footer
}else
{
	header('location: ../index.php'); 
}
 ob_end_flush(); 
?>