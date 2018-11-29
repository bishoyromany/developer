<?php 
	ob_start();
	session_start();
    include 'includes/languages/english.php';	// languages
	$tag = isset($_GET['name']) ? filter_var($_GET['name'] , FILTER_SANITIZE_STRING) : null ;    
	$pageTitle = $tag;
	include "init.php"; // the paths file
?>

<h1 class="text-center"><?php echo $tag; ?></h1>
<div class="container">
	<div class="row">
    <?php
    // get the data
    $items = getData("items.* , users.Username , users.UserID , categories.Name as cat , categories.ID" , "items" , "WHERE tags LIKE '%$tag%' AND approve = ?" , 1 , "Item_ID" , "DESC" , "" ,"items&users&categories");
    // get the pagination number
    $limit = 21;
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1 ;
    $start = ($page - 1) * $limit ;
    $total = $con->query("SELECT FOUND_ROWS() AS total")->fetch()['total'];
    $pages = ceil($total / $limit);
    // start fetching data
    if (!empty($items)) {
			foreach ($items as $item) {
			// get comments number
			$stmt2 = $con->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM comments WHERE item_id = ? AND status = 1");
			$stmt2->execute(array($item['Item_ID']));
			$commentsNom = $con->query("SELECT FOUND_ROWS() AS numbers")->fetch()['numbers'];
	?>
				<div class="col-sm-6 col-md-4 items_container_space">
				<div class="items">
					<a class="href_items" href="items.php?catid=<?php echo $item['Cat_ID']; ?>&catname=<?php echo $item['cat']; ?>&itemid=<?php echo $item['Item_ID']; ?>&itemname=<?php echo str_replace(" ", "-", $item['Name']); ?>">
						<img src="layout/images/test.jpg" class="item_img img-responsive img-center">

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
							<h3 class="item_title"><a class="href_items" href="items.php?catid=<?php echo $item['Cat_ID']; ?>&catname=<?php echo $item['cat']; ?>&itemid=<?php echo $item['Item_ID']; ?>&itemname=<?php echo str_replace(" ", "-", $item['Name']); ?>"> 	<?php echo limit_text($item['Name'] , 5); ?> </a>
							</h3>
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
			<a href="?name=<?php echo $tag; ?>&page=<?php echo $i ?>" class="<?php if ($page == $i){ echo "paginationactive";  } ?>"><?php echo $i; ?></a>
	<?php
			endfor;
			echo "</div>";
		}else{
			echo "<div class='nice_message'>Sorry no items to show :( </div> ";
		} ?>
</div>	
<?php
include $tpl.'footer.php'; // footer include
 ob_end_flush(); 
?>
