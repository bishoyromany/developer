<?php
ob_start();	
	session_start();
		if (isset($_SESSION['Username']))
		{
		include 'includes/languages/english.php';	// languages 
		$pageTitle =lang('DashBOARD') ;
		include 'init.php';
		/* start Dashboard page */}
	 	else
	 	{
	 	 header('location: index.php'); 
	   	}
// check if user or admin
if (proveAdmin() == 1)
{
	?>
		<div class="container home-stats text-center">
			<h1><?php echo lang('DASHBOARD') ?></h1>
			<div class="rwo">
				<!-- Total members -->
				<div class="col-sm-6 col-md-3">
					<div class="stat hvr-bounce-to-left st-members">
						<span class="hvr-pulse-grow">
							<i class="fa fa-users"></i>
						</span>
						<div class="info">
							<a href="members.php?do=Manage">	
								<?php echo lang('TOTALMEMBERS') ?>
								<div class="member-num">
							 		<?php echo countItem('UserID' , 'users'); ?>
							 	</div>
							</a>
					</div>
					</div>
				</div>
				<!-- Total pending members -->
				<div class="col-sm-6 col-md-3">
					<div class="stat hvr-bounce-to-right st-pending">
						<span class="hvr-pulse-grow"><i class="fa fa-user-plus"></i></span>
						<div class="info">
							<a href="members.php?do=Manage&page=Pending">
							 	<?php echo lang('PENDINGMEMBERS') ?>
								<div class="pending-num">
									<?php echo checkItem('RegStatus', 'users' , 0); ?>
								</div>
							</a>
						</div>
					</div>
				</div>
				<!-- Total Items -->
				<div class="col-sm-6 col-md-3">
					<div class="stat hvr-bounce-to-left st-items">
						<span class="hvr-pulse-grow"><i class="fa fa-cart-plus"></i></span>
						<div class="info">
							<a href="items.php?do=Manage">
							 	<?php echo lang('TOTALITEMS') ?>
								<div class="items-num">
									<?php echo CountItem('Item_ID','items');  ?>
								</div>
							</a>
						</div>
					</div>
				</div>
				<!-- Total comments -->
				<div class="col-sm-6 col-md-3">
					<div class="stat hvr-bounce-to-right st-comments">
						<span class="hvr-pulse-grow"><i class="fa fa-comment"></i></span>
						<div class="info">
							<a href="comments.php?do=Manage">
							 	<?php echo lang('TOTALCOMMENTS') ?>
								<div class="comments-num">
									<?php echo CountItem('c_id' , 'comments');  ?>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>



		<div class="latest">
			<div class="container latest-con">
				<div class="rwo">
					<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-users"></i> <?php echo lang('LATESTREGISTERED') ?>
								<span class="latest-hide"><i class="fa fa-plus pull-right fa-lg"></i></span>
							</div>
							<div class="panel-body">
								<ul class="list-unstyled last-members">
									<?php
										// check if there is record or not
											$latestUsers = getLatest("*", "users", "UserID",5 , "WHERE GroupID != 1");
										if (! empty($latestUsers)) {
											
											// echo latest users registered 
											foreach ($latestUsers as $user) {
												echo '<li>'. $user['Username'].'<br>';

												if ($user['RegStatus'] == 0) {
													echo '<a href="members.php?do=Pending&UserID='.$user['UserID'].'"><span class="btn btn-info pull-right hvr-icon-pulse-grow"><i class="fa fa-check hvr-icon"></i> ' .lang('ACTIVATE'). ' </span></a>';
												}elseif ($user['RegStatus'] == 1)
												{
													echo '<a href="members.php?do=Deactive&UserID='.$user['UserID'].'"><span class="btn btn-primary pull-right hvr-icon-pulse-grow"><i class="fa fa-times hvr-icon"></i> '.lang('DEACTIVATE').' </span></a>';
												}
												echo '<a href="members.php?do=Edit&UserID='.$user['UserID'].'"><span class="btn btn-success pull-right hvr-icon-pulse-grow"><i class="fa fa-edit hvr-icon"></i> ' .lang('EDIT').' </span></a> ';
												echo ' <a href="members.php?do=Delete&UserID='.$user['UserID'].'"><span class="btn btn-danger pull-right hvr-icon-pulse-grow confirm"><i class="fa fa-times hvr-icon"></i> '.lang('DELETE').' </span></a> ';
												echo'</li>';
											}
										}else
										{
											echo '<div class="nice_message" style="background-color:#DDD;">No Users To Show</div>';
										}
									?>
								</ul>
								<a href="members.php?do=Add"><button class="btn btn-primary btn-block"><i class="fa fa-plus"></i> <?php echo lang('NEWMEMBER') ?></button></a>
							</div>
						</div>
					</div>
					<!-- end Latest signup users -->

					<!-- Start Latest items -->
					<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-tag"></i> <?php echo lang('LATESTITEMS') ?>
								<span class="latest-hide"><i class="fa fa-plus pull-right fa-lg"></i></span>
							</div>
							<div class="panel-body">
								<ul class="list-unstyled last-items">
									<?php 
											// get latest items
											$latestItems = getLatest("*" , "items" , "item_ID" , 5);
											// fetch data
										if (! empty($latestItems)) {

											foreach ($latestItems as $item) {
												echo "<li>".$item['Name']."<br>";
													if ($item['approve'] == 0) {
														echo "<a class='btn btn-info pull-right' href='items.php?do=approve&itemID=".$item['Item_ID']."'><i class='fa fa-check'></i>  Approve</a>";
													}elseif ($item['approve'] == 1) {
														echo "<a class='btn btn-primary pull-right' href='items.php?do=disapprove&itemID=".$item['Item_ID']."'><i class='fa fa-times'></i>    disapprove</a>";
													}
													echo '<a href="items.php?do=Edit&itemID='.$item['Item_ID'].'"><span class="btn btn-success pull-right hvr-icon-pulse-grow"><i class="fa fa-edit hvr-icon"></i> ' .lang('EDIT').' </span></a> ';
													echo ' <a href="items.php?do=Delete&itemID='.$item['Item_ID'].'"><span class="btn btn-danger pull-right hvr-icon-pulse-grow confirm"><i class="fa fa-times hvr-icon"></i> '.lang('DELETE').' </span></a> ';
													echo'</li>';
												echo "</li>";
											}
										}else
										{
											echo '<div class="nice_message" style="background-color:#DDD;">No Items To Show</div>';									
										}

									?>
								</ul>
								<a href="items.php?do=Add"><button class="btn btn-primary btn-block"><i class="fa fa-plus"></i> New Item</button></a>							
							</div>
						</div>
					</div>
					<!-- End Latest items -->
					<!-- start Latest comments -->
					<div class="col-md-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-comment"></i> Latest 5 Comments
								<span class="latest-hide"><i class="fa fa-plus pull-right fa-lg"></i></span>
							</div>
							<div class="panel-body">
								<ul class="list-unstyled last_comments">
									<?php 
										$stmt = $con->prepare("
											SELECT 
												comments.* , items.Name , users.Username FROM comments 
											INNER JOIN
												items ON items.Item_ID = comments.item_id
											INNER JOIN users ON users.UserID = comments.user_id
											ORDER BY c_id DESC LIMIT 5
											");
										$stmt->execute();
										$comments = $stmt->fetchAll();

										if (! empty($comments)) {
											foreach ($comments as $comment) {
											echo "<li>";
													echo "<span><a href='members.php?do=Edit&UserID=".$comment['user_id']."'>".$comment['Username']."</a></span>";
													echo "<p>".$comment['comment']."</p>";
													echo "<div class='fix'></div>";
													echo "<div class='buttons'>";
														echo "<a class='btn btn-danger confirm' href='comments.php?do=Delete&comid=".$comment['c_id']."'><i class='fa fa-times'></i> Delete</a>";
														echo "<a class='btn btn-success' href='comments.php?do=Edit&comid=".$comment['c_id']."'>
															<i class='fa fa-edit'></i>
															Edit</a>";
														if ($comment['status'] == 0) {
														echo "<a class='btn btn-info' href='comments.php?do=approve&comid=".$comment['c_id']."'>
														<i class='fa fa-check'></i> Approve</a>";
														}elseif ($comment['status'] == 1) {
															echo "<a class='btn btn-primary' href='comments.php?do=disapprove&comid=".$comment['c_id']."' style='font-size:12px;'>
														<i class='fa fa-times'></i> Disapprove</a>";
														}
													echo "</div>";
											echo "</li>";
											}
										}else
										{
											echo '<div class="nice_message" style="background-color:#DDD;">No Comments To Show</div>';
										}
									?>
									<li><a class="btn btn-primary btn-block" href="comments.php?do=Add"><i class="fa fa-plus"></i> Add New Comment</a></li>
								</ul>
							</div>
						</div>
					</div>
					<!-- end Latest comments -->
				</div>
			</div>
		</div>
	<?php
	/* start Dashboard page */
 	include $tpl.'footer.php';
}else
{
	header('location: ../index.php'); 
}
ob_end_flush();
?>
