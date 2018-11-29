<div class="admin_navbar">
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
	      <a class="navbar-brand hvr-grow-shadow" href="../index.php"><?php echo lang('HOME'); ?></a>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="app-nav">
	      <ul class="nav navbar-nav">
	        <li><a href="dashboard.php">Dashboard</a></li>
	        <li><a href="categories.php"><?php echo lang('SECTIONS'); ?></a></li>
	        <li><a href="items.php"><?php echo lang('ITEMS'); ?></a></li>
	        <li><a href="members.php"><?php echo lang('MEMBERS'); ?></a></li>
	        <li><a href="comments.php"><?php echo lang('COMMENTS'); ?></a></li>
	      </ul>
	      <!-- Profile links -->
	      <ul class="nav navbar-nav navbar-right">
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="user hvr-icon-pulse"><i class="fa fa-user hvr-icon"></i>  <?php echo $_SESSION['FullName']; ?> &nbsp; </span> <span class="hvr-icon-hang"> <span class="fa fa-caret-down hvr-icon"> </span></span> </a>
	          <ul class="dropdown-menu">
	            <li>
	            	<a href="members.php?do=Edit&UserID=<?php echo $_SESSION['ID'] ?>" class="hvr-icon-pulse-grow">
	            		<?php echo lang('EDIT'); ?> 
	            		<i class="fa fa-edit hvr-icon"></i> 
	            	</a>
	            </li>

	            <li>
	            	<a href="members.php?do=Settings" class="hvr-icon-spin">
	            		<?php echo lang('SETTINGS'); ?>
	            		<i class="fa fa-cog hvr-icon"></i>
	            	</a>
	            </li>

	            <li>
	            	<a href="logout.php" class="hvr-icon-wobble-horizontal"> 
	            		<?php echo lang('LOGOUT'); ?> 
	            		<i class="fa fa-chevron-circle-right hvr-icon"></i>
	            	</a>
	            </li>

	          </ul>
	        </li>
	      </ul>
	    </div>
	  </div>
	</nav>
</div>