<?php 
	ob_start();
	session_start();
	include 'includes/languages/english.php';	// languages
	$pageTitle = str_replace("-"," ",$_GET['catname']);
	include "init.php"; // the paths file
	// category name and id
	$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : null ;
	$catname = isset($_GET['catname']) ? $_GET['catname'] : 0 ;
	$catstrname = str_replace("-", " ", $catname);
	if (isset($catid))
	{
		$cats = getAll("categories" , "ID" , $catid , "ID DESC" , null , null);
		// start pagination
		// the user input into url
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;
		$perpage = isset($_GET['perpage']) && $_GET['perpage'] <= 50 ? (int)$_GET['perpage'] : 21 ;
		// position
		$start = $page > 1 ? ($page * $perpage) - $perpage : 0 ;
		$items = getData("items.* , users.Username , categories.Name as cat , categories.ID" , "items" , "WHERE approve = 1 AND Cat_ID = {$catid} OR approve = 1 AND parent = ?", $catid , "Item_ID" , "DESC" , "{$start},{$perpage}" , "items&users&categories");
		// pages
		$total = $con->query("SELECT FOUND_ROWS() as total")->fetch()['total'];
		$pages = ceil($total / $perpage) ;
		
?>
<h1 class="text-center"> <?php foreach ($cats as $cat) { echo $cat['Name']; } ?> </h1>
<div class="move_links container">
	<span>
		<a href="index.php">Home</a>
		 <i class="fa fa-arrow-right"></i> 
		 <a href='category.php?catid=<?php echo $catid ?>&catname=<?php echo $catname ?>'><?php echo $catstrname ?></a>
	</span>
</div>


<div class="container">
	<div class="row">

	<?php 
		if (!empty($items)) {
			foreach ($items as $item) {
			// get comments number
			$stmt2 = $con->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM comments WHERE item_id = ? AND status = 1");
			$stmt2->execute(array($item['Item_ID']));
			$commentsNom = $con->query("SELECT FOUND_ROWS() AS numbers")->fetch()['numbers'];
			// get the image to check if it there is image or not
			$stmtimage = $con->prepare("SELECT image FROM items WHERE Item_ID = ?");
			$stmtimage->execute(array($item['Item_ID']));
			$image_i = $stmtimage->fetch()['image'];
	?>
				<div class="col-sm-6 col-md-4 items_container_space">
				<div class="items">
					<!-- pretty data when hover -->
					<div class="pretty_data">
						<ul class="list-unstyled ul_pretty">
						<li class="li1">Country Made <i class="fa fa-building fa-fw"></i> : <?php echo $item['Country_Made']; ?></li>
							<li class="li2">Category <i class="fa fa-tag fa-fw"></i> : <a href="category.php?catid=<?php echo $item['ID']; ?>&catname=<?php echo $item['cat']; ?>"><?php echo $item['cat']; ?></a></li>
							<li class="li3">Quantity : <?php echo $item['Quantity']; ?></li>							
							<li class="li4">Tags <i class="fa fa-tags fa-fw"></i> : 
								<?php
									$alltags_t = str_replace(" " , "" , $item['tags']);
									$alltags = explode("," , $alltags_t);
									if (!empty($alltags_t)):
										foreach($alltags as $tag)
										{
											$small_tags = strtolower($tag);
											echo "<a href='tags.php?name=".$small_tags."'>". $tag . "</a>";
										}
									else:
										echo "<span>No Tags To Show . </span>";
									endif;
								?> 
							</li>							
							<hr>
							<div class="desc_pretty" style="color:white;">
								<?php echo $item['Description']; ?>
							</div>
							<span class="hide_pretty"><i class="fa fa-chevron-circle-up fa-fw fa-3x"></i></span>
						</ul>
					</div>
					<!-- end pretty data when hover -->
					<a class="href_items" href="items.php?catid=<?php echo $catid; ?>&catname=<?php echo $catname; ?>&itemid=<?php echo $item['Item_ID']; ?>&itemname=<?php echo str_replace(" ", "-", $item['Name']); ?>">
					<?php if (empty($image_i)){ ?>
						<img src="items/item.jpg" class="item_img img-responsive img-center">
					<?php } elseif(!empty($image_i)) { ?>
						<img src="items/<?php echo $image_i; ?>" class="item_img img-responsive img-center">
					<?php } 
						if (isset($_SESSION['Admin']) && $_SESSION['GroupID'] > 0) { ?>
							<!-- Edit item for admin --> 
							<a class='edit_item' href="profile.php?do=edit-item&item_edit_id=<?php echo $item['Item_ID'] ?>&userid=<?php echo $_SESSION['ID'] ?>"><i class="fa fa-edit"></i></a>
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
							<h3 class="item_title"><a class="href_items" href="items.php?catid=<?php echo $item['Cat_ID']; ?>&catname=<?php echo $item['cat']; ?>&itemid=<?php echo $item['Item_ID']; ?>&itemname=<?php echo str_replace(" ", "-", $item['Name']); ?>"> 	<?php echo limit_text($item['Name'] , 5); ?> </a>
							</h3>
						</li>
						<li class="price_veiws_comments">
							<div class="price_div">Price <i class="fa fa-money fa-fw fa-md"></i> : <span class="price">$<?php echo $item['Price']; ?> </span><span class="more_details"><i class="fa fa-bars fa-fw fa-2x"></i></span></div>
							<span class="comments">Comments : <?php echo $commentsNom; ?> <i class="fa fa-comments fa-fw"></i> </span>
							<span class="views">Views : <?php echo $item['count_num']; ?> <i class="fa fa-eye fa-fw"></i> </span>
							<div class="fix"></div>
						</li>
						<li>
							<div class="item_description" style="word-wrap: break-word;"><?php echo limit_text($item['Description'] , 23); ?></div>
						</li>
						<li class="member_date">
							<span>Sold by <i class="fa fa-user fa-fw"></i> : <a href="profile.php?UserID=<?php echo $item['Member_ID']; ?>" class="item_member"><?php echo $item['Username']; ?></a></span>
							<span class="pull-right">Date <i class="fa fa-calendar fa-fw"></i> : <span class="item_date"><?php echo $item['Add_Date']; ?></span></span>
						</li>
					</ul>

				</div>
			</div>
		
	<?php 	
			}
			echo "</div>";
			echo "<div class='paginationa'>";
			for ($i=1; $i <= $pages ; $i++):
	?>
			<a href="?catid=<?php echo $cat['ID'] ?>&catname=<?php echo str_replace(" ","-",$cat['Name']) ?>&page=<?php echo $i ?>$perpage=<?php echo $perpage ?>" class="<?php if ($page == $i){ echo "paginationactive";  } ?>"><?php echo $i; ?></a>
	<?php
			endfor;
			echo "</div>";
		}elseif (empty($items)) {
			echo "<div class='nice_message'>Sorry no items to show :( </div> ";
		} ?>
</div>	
<?php
	}else
	{
		header('location: index.php');
	}
include $tpl.'footer.php'; // footer include
 ob_end_flush(); 
?>
