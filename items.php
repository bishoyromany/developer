<?php 
	ob_start();
	SESSION_START();
	include "includes/languages/english.php";
	$pageTitle =isset($_GET['itemname']) ? str_replace("-"," ",$_GET['itemname']) : "Unknown item";
	include "init.php";
	// get the item id and name
	$itemid = isset($_GET['itemid']) &&  is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;
	$itemname = isset($_GET['itemname']) ? $_GET['itemname'] : null ;
	$itemstrname = str_replace("-", " ", $itemname);
	// cat id and name 
	$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : null ;
	$catname = isset($_GET['catname']) ? $_GET['catname'] : null ;
	$catstrname = str_replace("-", " ", $catname);
	// get the data 
	$items = getData("items.* , users.Username , users.UserID" , "items" , "WHERE Item_ID = $itemid AND approve =?" ,  1 , "Item_ID" , "DESC", 1 , "items&users&categories");

if (!empty($items))
{
	foreach ($items as $item_id_link) {
	}
?>
	<h1 class="text-center"><?php echo $catstrname; ?></h1>
	<!-- The Dynamic links to know where you are -->
	<div class="move_links container">
		<span>
			<a href="index.php">Home</a>
			 <i class="fa fa-arrow-right"></i> 

			 <?php if (isset($_GET['catname2'])) { echo '<a href="category.php?catid='.$_GET['catid'].'&catname='.$_GET['catname2'].'">'.$_GET['catname2'].'</a> <i class="fa fa-arrow-right"></i> '; } ?>

			 <a href='
			 <?php if (isset($_GET["catname"])){ echo "category.php?catid=".$catid."&catname=".$catname; }
			 elseif (isset($_GET["username"]) && !isset($_GET["name"])) { echo "profile.php"; }

			 elseif (isset($_GET["name"])) { echo "profile.php?UserID=".$item_id_link["Member_ID"]; } ?>
			 '>
			 <?php 
			 if (isset($_GET['catname'])) { echo $catstrname; }
			 elseif ( isset($_GET['username'])) { echo str_replace("-", " ", $_SESSION['FullName']); }
			 elseif ( isset($_GET['name'])) { echo str_replace("-", " ", $_GET['name']); }
			 else { echo "Unknown place"; } ?></a>

			 <i class="fa fa-arrow-right"></i> 
			 <a href='#'><?php echo $itemstrname; ?></a>
		</span>
	<!-- End dynamic links -->
	</div>
	<!-- fetching data container -->
	<div class="container all_items">
		<div class="row">
			<?php 
			foreach($items as $item) {
			// page views 
			$watched = $item['count_num'] + 1;
			$stmt_watch = $con->prepare("UPDATE items SET count_num = ? WHERE Item_ID = ?");
			$stmt_watch->execute(array($watched , $item['Item_ID']));
			?>
			<!-- Item Image -->
			<div class="col-md-4 image_item">
				<img src="layout/images/test.jpg" class="item_img img-responsive img-center">
			</div>
			<!-- Item h3 and describtion price and date and user -->
			<div class="col-md-8 contain_all">
				<h3 class="text-center"><?php echo $item['Name']; ?></h3>
				<div class="lead item_description_container"><?php echo $item['Description']; ?></div>
				<div class="price_btn">
				<span class="item_price">Price <i class="fa fa-money fa-fw"></i> : <span>$<?php echo $item['Price']; ?></span></span>

				<!-- The add CArt button -->
				<?php if (isset($_SESSION['Username'])) { ?>
				<a class="btn btn-success btn-lg">Add to cart <i class="fa fa-plus fa-fw"></i> </a>
				<?php } elseif (!isset($_SESSION['Username'])) { ?>
					<input type="submit" disabled="disabled" class="btn btn-success btn-lg" value="SignIn to buy" title="Signin/up to add to cart">
				<?php } ?>
				</div>
				<div class="status_quantity">
					<span class="Status"><i class="fa fa-check fa-fw"></i> Item Status :<span> <?php if ($item['Status'] == 1) { echo "New"; }elseif ($item['Status'] == 2) { echo "Like New"; }elseif ($item['Status'] == 3) { echo "Used"; }elseif ($item['Status'] == 4) { echo "Old"; }else { echo "Status Unknown"; }; ?></span></span>

					<span class="Quantity"><i class="fa fa-tags fa-fw"></i> Quantity :<span> <?php echo $item['Quantity']; ?></span></span>

					<span class="Country_Made"><i class="fa fa-building fa-fw"></i> Country Made : <span><?php echo $item['Country_Made'] ?></span> </span>
				</div>
				<div class="item_tags">
					<span>
						Item Tags <i class="fa fa-tags fa-fw"></i> :
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
								echo "<div class='nice_message' style='display:inline-block;'>No Tags To Show . </div>";
							endif;
						?> 
					</span>
				</div>
				<div class="user_date">
					<span class="username_item">Sold By <i class="fa fa-user fa-fw"></i> :<a href="profile.php?UserID=<?php echo $item['Member_ID']; ?>"> <?php echo $item['Username']; ?></a></span>
					<span class="add_date_item">Added Date <i class="fa fa-calendar fa-fw"></i> :<span> <?php echo $item['Add_Date']; ?></span></span>
				</div>
			</div>
		</div>
	</div>
			<?php };?>
<!-- Start Comments section -->

<?php 

			if (isset($_POST['addcomment']) && $_SERVER['REQUEST_METHOD'] == "POST" && isset($_GET['comment']) && $_GET['comment'] == "add") {
				// get data
				$comment = filter_var($_POST['comment'] , FILTER_SANITIZE_STRING);
				$Username = $_SESSION['Username'];
				$UserID = $_SESSION['ID'];
				$Item_ID = filter_var($itemid , FILTER_SANITIZE_NUMBER_INT);
				$cat_id = filter_var($catid , FILTER_SANITIZE_NUMBER_INT);
				// get errors 
				$comment_errors = array();
				// the comment
				if (empty($comment)) {
					$comment_errors[] = "You can't leave Comment Field Empty";
				}
				// foreach errors if exist
				if (!empty($comment_errors)) {
					foreach ($comment_errors as $error) {
						"<div class='nice_message'>".$error."</div>";
					}
				}elseif (empty($comment_errors))
				{
					$stmtc = $con->prepare("INSERT INTO comments ( comment , comment_date , user_id , item_id , cat_id) VALUES ( ? , now() , ? , ? , ? )");
					$stmtc->execute(array($comment , $UserID , $Item_ID , $cat_id));
					$commentCount = $stmtc->rowCount();
				}

			}
	
?>


		<div class="container">
		<div class="row">
		<?php if (isset($_SESSION['Username'])){ ?>
		<div class="col-md-12 item_add_comment" id="add-comment">
			<h3 class="text-center">Add New comment <i class="fa fa-comment"></i></h3>
			<?php if (isset($commentCount) && $commentCount > 0){ ?>
			<div class='nice_message_success col-md-12'> Comment Added Succssfully but waiting admin approve . </div>
			<?php } ?>	
				<form class="form-horizanel" action="items.php?catid=<?php echo $catid; ?>&catname=<?php echo $catname; ?>&itemid=<?php echo $itemid; ?>&itemname=<?php echo str_replace(" ", "-", $itemname); ?>&comment=add#add-comment" method="POST">
				<div class="form-group">
					<label class="label-control col-md-2">Your Comment : </label>						
					<div class="col-md-10">	
					<textarea class="form-control" placeholder="Write Your Message Here..." style="min-height: 100px; max-height: 200px;" name="comment" required="required" 
					title="Write your comment here"></textarea>
					</div>
					</div>
					<div class="form-group">
						<div class="col-md-4 col-md-push-4">
						<input type="submit" name="addcomment" value="Add Comment" class="btn btn-success btn-block">
						</div>
					</div>
				</form>
		</div>


<?php 		
				}
			else { echo "<div class='nice_message'>You must <a href='signup.php'>Login /SignUp </a>To Add New Comment</div>"; } 
				// start comments section 
				// here is pagination Calc
				$limit = 5 ;
				$page = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;
				$start = ($page - 1) * $limit ;
				// start getting data
				$stmtcom = $con->prepare(" SELECT SQL_CALC_FOUND_ROWS 
					comments.* , users.Username , users.UserID FROM comments 
					INNER JOIN users ON users.UserID = comments.user_id 
					WHERE item_id = ? AND status = 1 ORDER BY c_id DESC LIMIT {$start},{$limit}
					");
				$stmtcom->execute(array($itemid));
				$commentsnum = $stmtcom->rowCount();
				$commentData = $stmtcom->fetchAll(); 
				// get comment number due to pagination
				$totalpages = $con->query("SELECT FOUND_ROWS() AS totalpages")->fetch()['totalpages'];
				$pages = ceil($totalpages / $limit);

			?>

			
			<div class="col-xs-12 items_comments_container">
				<h3 class="text-center">Item Comments <i class="fa fa-comments"></i></h3>
			<?php  if (!empty($commentData)){
				foreach ($commentData as $comment) {
					// check if image exists or no
					$stmt_photo = $con->prepare("SELECT image FROM users WHERE UserID = ?");
					$stmt_photo->execute(array($comment['UserID']));
					$img = $stmt_photo->fetch()['image'];
			?>
				<div class="comment_container col-xs-12">
					<div class="col-xs-2">
					<span class="image_comments_container">
					<?php if (empty($img)) { ?>
						<img src="uploads/avatar.jpg">
					<?php } elseif (!empty($img)) { ?>
						<img src="uploads/<?php echo $img; ?>">
					<?php } ?>
					</span>
					</div>
				<div class="col-xs-10">
					<span class="comment_username"><a href="profile.php?UserID=<?php echo $comment['user_id']; ?>"><i class="fa fa-user fa-fw"></i> <?php echo $comment['Username']; ?></a></span>
					<span class="comment_date"><i class="fa fa-calendar fa-fw"></i> <?php echo $comment['comment_date']; ?></span>
					<hr>
					<p class="lead col-xs-12 the_comment"><?php echo $comment['comment']; ?></p>
				</div>
				</div>
				<hr class="another_hr">
			<?php
				}
			?>
			</div>
		</div>

		</div>
	</div>		
		<div class="paginationa">
		<?php for ( $i = 1 ; $i <= $pages ; $i++ ) { ?>
			<a href="items.php?catid=<?php echo $catid; ?>&catname=<?php echo $catname; ?>&itemid=<?php echo $itemid; ?>&itemname=<?php echo str_replace(" ", "-", $itemname); ?>&page=<?php echo $i ?>"<?php if ($i == $page) { echo "class='paginationactive'"; } ?> ><?php echo $i ?></a>
		<?php } ?>
		</div>
<?php } elseif(empty($commentData)) {  echo "<div class='nice_message'>Sorry No Comments To Show </div>";  } ?>







		<div class="row">
			<!-- Random Items -->
			<div class="col-md-12 ranodm_items">
				<h3 class="text-center random_item_head">Random Items</h3><br>
				<?php $random_items = getdata ("items.* , users.Username , users.UserID , categories.Name as cat" , "items" , "WHERE Cat_ID = $catid AND approve = 1 AND Item_ID != ? " , $itemid , "ceil (RAND() * (SELECT MAX(Item_ID) FROM items))         " , "ASC" , 6 ,"items&users&categories");
				if (!empty($random_items)) {

					foreach($random_items as $randomitem) {
					// get comments number
					$stmt2 = $con->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM comments WHERE item_id = ? AND status = 1");
					$stmt2->execute(array($randomitem['Item_ID']));
					$commentsNom = $con->query("SELECT FOUND_ROWS() AS numbers")->fetch()['numbers'];
				?>
				<div class="all_random_items">
				<div class="col-sm-6 col-md-4 items_container_space">
				<div class="items">
					<a class="href_items" href="items.php?catid=<?php echo $catid; ?>&catname=<?php echo $catname; ?>&itemid=<?php echo $randomitem['Item_ID']; ?>&itemname=<?php echo str_replace(" ", "-", $randomitem['Name']); ?>">
						<img src="layout/images/test.jpg" class="item_img img-responsive img-center">

						<?php if (isset($_SESSION['Admin']) && $_SESSION['GroupID'] > 0) { ?>
							<!-- Edit item for admin -->
							<a class='edit_item' href="admin/items.php?do=Edit&itemID=<?php echo $randomitem['Item_ID'] ?>"><i class="fa fa-edit"></i></a>
							<!-- Delete Item for admin -->
							<a class='delete_item confirm' href="admin/items.php?do=Delete&itemID=<?php echo $randomitem['Item_ID'] ?>"><i class="fa fa-close"></i></a>
						<?php }elseif (isset($_SESSION['Username']) && !isset($_SESSION['Admin']) && isset($_SESSION['ID']) && $_SESSION['ID'] == $randomitem['Member_ID']) {
						?>
						<!-- Edit item for User -->
						<a class='edit_item' href="admin/items.php?do=Edit&itemID=<?php echo $randomitem['Item_ID'] ?>"><i class="fa fa-edit"></i></a>
						<!-- Delete Item for User -->
						<a class='delete_item confirm' href="admin/items.php?do=Delete&itemID=<?php echo $randomitem['Item_ID'] ?>"><i class="fa fa-close"></i></a>
						<?php
						} ?>
					</a>
					<ul class="list-unstyled">
						<li>
							<h3 class="item_title">
								<a class="href_items" href="items.php?catid=<?php echo $catid; ?>&catname=<?php echo $randomitem['cat']; ?>&itemid=<?php echo $randomitem['Item_ID']; ?>&itemname=<?php echo str_replace(" ", "-", $randomitem['Name']); ?>">
									<?php echo limit_text($randomitem['Name'] , 5); ?> 
								</a>
							</h3>
						</li>
						<li class="price_veiws_comments">
							<div class="price_div">Price <i class="fa fa-money fa-fw fa-md"></i> : <span class="price">$<?php echo $randomitem['Price']; ?> </span></div>
							<span class="comments">Comments : <?php echo $commentsNom; ?> <i class="fa fa-comments fa-fw"></i> </span>
							<span class="views">Views : <?php echo $randomitem['count_num']; ?> <i class="fa fa-eye fa-fw"></i> </span>
							<div class="fix"></div>
						</li>
						<li>
							<div class="item_description" style="word-wrap: break-word;"><?php echo limit_text($randomitem['Description'] , 23); ?></div>
						</li>
						<li class="member_date">
							<span>Sold by <i class="fa fa-user fa-fw"></i> : <a href="profile.php?UserID=<?php echo $randomitem['Member_ID']; ?>" class="item_member"><?php echo $randomitem['Username']; ?></a></span>
							<span class="pull-right">Date <i class="fa fa-calendar fa-fw"></i> : <span class="item_date"><?php echo $randomitem['Add_Date']; ?></span></span>
						</li>
					</ul>

				</div>
			</div>
				</div>
				<?php }}elseif (empty($random_items)) { echo "<div class='nice_message'>Sorry no random items to show in this category</div>"; } ?>
			</div>
			<!-- End Random Items -->
		</div>
	

<?php 
}

else { echo "<div class='nice_message'>There's No Such Item Or it hasn't approved by admin yet</div>";  }

include $tpl."footer.php";
ob_end_flush();
?>
