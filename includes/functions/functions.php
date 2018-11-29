<?php
	
	/*### function getData this function can get all database v4.0 ###
	################## Tutrial how  to use IT ########################
		$what : Select what from database forexample * ;
		$table : the table u wanan select data from forexample  items ;
		$where : where something equal something u can leave it empty :
		forexample we can say WHERE Item_ID = ;
		$eual : the value that $where should qual : 
		forexample Item_ID = $itemid ;
		$by : Order the data by what forexample Item_ID ;
		$order : The type of the order : if you left it empty it auto equals DESC by you can change it :
		forexample ASC order way
		$innerjoin : This is the most important var due to it changes database for several ways :
		*1* if you left it empty no inner JOIN would be happened just select data from one table :
		*2* if you wrote comments&users&items&categories : you get data of comments and users and categories tables you write data you need into [ $what ]:
		*3* if you wrote items&users&categories : you get data of items and users and categories tables you write data you need into [ $what ]:
	*/
	function getData ($what , $table , $where = null , $equal = null , $by ,$order = "DESC" , $limit = null , $innerjoin = null)
	{
		global $con;
		$sql_limit = !empty($limit) ? "LIMIT ".$limit : null ; 
		// getting one table
		if ($innerjoin == null):
			$get_data_sql = $con->prepare("SELECT SQL_CALC_FOUND_ROWS $what FROM $table $where ORDER BY $by $order $sql_limit");
			$get_data_sql->execute(array($equal));
			$get_all_data = $get_data_sql->fetchAll();
		// for comments and users
		elseif ($innerjoin == "comments&users&items&categories"):
			$get_data_sql = $con->prepare("SELECT SQL_CALC_FOUND_ROWS $what FROM $table INNER JOIN users ON users.UserID = comments.user_id INNER JOIN categories ON categories.ID = comments.cat_id INNER JOIN items ON items.Item_ID = comments.item_id $where  ORDER BY $by $order $sql_limit");
			$get_data_sql->execute(array($equal));
			$get_all_data = $get_data_sql->fetchAll();
		// for items and users
		elseif ($innerjoin == "items&users&categories"):
			$get_data_sql = $con->prepare("SELECT SQL_CALC_FOUND_ROWS $what FROM $table INNER JOIN users ON users.UserID = items.Member_ID INNER JOIN categories ON categories.ID = items.Cat_ID $where  ORDER BY $by $order $sql_limit");
			$get_data_sql->execute(array($equal));
			$get_all_data = $get_data_sql->fetchAll();
		endif;
			return $get_all_data;

	}
	
	/*
		Get all function
		cats and items and categories
	*/
		function getAll ($from , $where ,$equal ,$order , $id ,$itemfunc = null)
		{
			global $con;
			if ($equal == null)
			{
				$getAll = $con->prepare("SELECT * FROM $from");
				$getAll->execute();
				$gotAll = $getAll->fetchAll();
				return $gotAll;
			}elseif ($itemfunc == null) {
				$getAll = $con->prepare("SELECT * FROM $from WHERE $where = ? ORDER BY $order");
				$getAll->execute(array($equal));
				$gotAll = $getAll->fetchAll();
				return $gotAll;
			}elseif ($itemfunc !==null && $itemfunc == "activeitems") {
				$getAll = $con->prepare("
					SELECT 
						items.* , users.Username
					FROM items 
					INNER JOIN users ON users.UserID = items.Member_ID 
						WHERE $where = ? AND Quantity > 0 
					ORDER BY  $order
					");
				$getAll->execute(array($equal));
				$gotAll = $getAll->fetchAll();
				if ($getAll->rowCount() !== 0) {
					return $gotAll;

				}elseif ($getAll->rowCount() == 0) { return 1; }
				

			}

			elseif ($itemfunc == "random") {
				$getAll = $con->prepare("
					SELECT 
						items.* , users.Username 
					FROM items 
					INNER JOIN users ON users.UserID = items.Member_ID 
						WHERE $where = ?
						AND Item_ID != ? 
					ORDER BY  $order 
					LIMIT 6
					");
				$getAll->execute(array($equal , $id));
				$gotAll = $getAll->fetchAll();
				return $gotAll;
			}
		};



	/* seeing who is admin and who is not function */
	function proveAdmin()
	{
		global $con;
		$admin = $con->prepare("SELECT GroupID FROM users WHERE UserID = ? AND Username = ? AND GroupID = ?");
		$admin->execute(array($_SESSION['ID'] , $_SESSION['Username'] , $_SESSION['GroupID']));
		$existadmin = $admin->rowCount(); 
		$adminprove = $admin->fetch();
		if ($existadmin > 0 && $adminprove['GroupID'] == 1) {
			return 1 ;
		}elseif ($existadmin > 0 && $adminprove['GroupID'] == 0) {
			return 0 ;
		}
	};
	/* 
		The title function that Echo The page title in case The page has The var $pageTitle and echo Default Title for others
	*/
		function getTitle()
		{
			global $pageTitle;
			if (isset($pageTitle)) {

				echo  $pageTitle;
				
			}else
			{
				echo "Default";
			}
		};



/*
	//////////	 	Redirection and Alert Function v1.3	  		///////////

	** 			This function can show errors info warning danger 		*1*
	** 			It can redirect to home page or previous page 			*2*
	** 			you can set the redirect time using [$seconds]			*3*
	** 			you can chosse the redirection type using [$place]		*4*
	*** 		$place ='active'; go to pre page no exist go to home 	*5*
	** 			you can chosse the error type using [$alert_info] 		*6*
	** 			, [$alert_warning] or [$alert_danger] ; 				* *
	***		****	** 		very Important  		****	*****		* *
	*** 		To activate Redirection use this var 					*7*
	* 			[$alert_redirect_home or back or custom] 				* *
	*** 		To activate The Message first info    etc... 			*8*
	* 			use this var [$alert_info1 == 'message';] etc... 		* *

*/
	function redirectAlert()
	{		
		// the alerts messages
		global 	$alertMsg  ; 
		global 	$alertMsg1 ; 
		global 	$alertMsg2 ; 
		global 	$alertMsg3 ; 
		global 	$alertMsg4 ;
		global 	$alertMsg5 ;
		// alert info message 
		global	$alert_success  ; 
		global	$alert_success1 ; 
		global	$alert_success2 ; 
		global	$alert_success3 ; 
		global	$alert_success4 ;
		global	$alert_success5 ;
		// end alert info message 
		// alert info message 
		global	$alert_info  ; 
		global	$alert_info1 ; 
		global	$alert_info2 ; 
		global	$alert_info3 ; 
		global	$alert_info4 ;
		global	$alert_info5 ;
		// end alert info message 
		// alert warning mesage 
		global	$alert_warning  ; 
		global	$alert_warning1 ; 
		global	$alert_warning2 ; 
		global	$alert_warning3 ; 
		global	$alert_warning4 ;
		global	$alert_warning5 ;
		// end alert warning message

		// start alert danger message
		global	$alert_danger  ; 
		global	$alert_danger1 ; 
		global	$alert_danger2 ; 
		global	$alert_danger3 ; 
		global	$alert_danger4 ;
		global	$alert_danger5 ;
		// end alert danger message

		// start alert redirect message
		global 	$seconds 		 		;
		global	$pageurgoingto 			;
		global	$alert_redirect_home  	;
		global	$alert_redirect_back  	;
		global	$alert_redirect_custom  ;
		global 	$place 			 		;
		// end alert redirect message
		// start alert success


			if (!empty($alert_success1)) {
				function alert_success1 ($alert_success1)
				{
					echo '<div class="alert alert-success aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_success1.'.</div>';
				}
				alert_success1($alert_success1);
			}


			if (!empty($alert_success2)) {
				function alert_success2 ($alert_success2)
				{
					echo '<div class="alert alert-success aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_success2.'.</div>';
				}
				alert_success2($alert_success2);
			}


			if (!empty($alert_success3)) {
				function alert_success3 ($alert_success3)
				{
					echo '<div class="alert alert-success aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_success3.'.</div>';
				}
				alert_success3($alert_success3);
			}


			if (!empty($alert_success4)) {
				function alert_success4 ($alert_success4)
				{
					echo '<div class="alert alert-success aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_success4.'.</div>';
				}
				alert_success4($alert_success4);
			}


			if (!empty($alert_success5)) {
				function alert_success5 ($alert_success5)
				{
					echo '<div class="alert alert-success aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_success5.'.</div>';
				}
				alert_success5($alert_success5);
			}
		// end alert success


		// start alert info messages limited 5 alerts per page  


			if (!empty($alert_info1)) {
				function alert_info1 ($alert_info1)
				{
					echo '<div class="alert alert-info aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_info1.'.</div>';
				}
				alert_info1($alert_info1);
			}


			if (!empty($alert_info2)) {
				function alert_info2 ($alert_info2)
				{
					echo '<div class="alert alert-info aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_info2.'.</div>';
				}
				alert_info2($alert_info2);
			}


			if (!empty($alert_info3)) {
				function alert_info3 ($alert_info3)
				{
					echo '<div class="alert alert-info aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_info3.'.</div>';
				}
				alert_info3($alert_info3);
			}


			if (!empty($alert_info4)) {
				function alert_info4 ($alert_info4)
				{
					echo '<div class="alert alert-info aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_info4.'.</div>';
				}
				alert_info4($alert_info4);
			}


			if (!empty($alert_info5)) {
				function alert_info5 ($alert_info5)
				{
					echo '<div class="alert alert-info aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_info5.'.</div>';
				}
				alert_info5($alert_info5);
			}
		// end alert info

		// start alert warnin

			if (!empty($alert_warning1)) {
				function alert_warning1 ($alert_warning1)
				{
					echo '<div class="alert alert-warning aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_warning1.'.</div>';
				}
				alert_warning1($alert_warning1);
			}
			if (!empty($alert_warning2)) {
				function alert_warning2 ($alert_warning2)
				{
					echo '<div class="alert alert-warning aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_warning2.'.</div>';
				}
				alert_warning2($alert_warning2);
			}
			if (!empty($alert_warning3)) {
				function alert_warning3 ($alert_warning3)
				{
					echo '<div class="alert alert-warning aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_warning3.'.</div>';
				}
				alert_warning3($alert_warning3);
			}
			if (!empty($alert_warning4)) {
				function alert_warning4 ($alert_warning4)
				{
					echo '<div class="alert alert-warning aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_warning4.'.</div>';
				}
				alert_warning4($alert_warning4);
			}
			if (!empty($alert_warning5)) {
				function alert_warning5 ($alert_warning5)
				{
					echo '<div class="alert alert-warning aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_warning5.'.</div>';
				}
				alert_warning5($alert_warning5);
			}
		// end alert warnin


		// start alert danger

			if (!empty($alert_danger1)) {
				function alert_danger1 ($alert_danger1)
				{
					echo '<div class="alert alert-danger aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_danger1.'.</div>';
				}
				alert_danger1($alert_danger1);
			}
			if (!empty($alert_danger2)) {
				function alert_danger2 ($alert_danger2)
				{
					echo '<div class="alert alert-danger aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_danger2.'.</div>';
				}
				alert_danger2($alert_danger2);
			}
			if (!empty($alert_danger3)) {
				function alert_danger3 ($alert_danger3)
				{
					echo '<div class="alert alert-danger aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_danger3.'.</div>';
				}
				alert_danger3($alert_danger3);
			}
			if (!empty($alert_danger4)) {
				function alert_danger4 ($alert_danger4)
				{
					echo '<div class="alert alert-danger aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_danger4.'.</div>';
				}
				alert_danger4($alert_danger4);
			}
			if (!empty($alert_danger5)) {
				function alert_danger5 ($alert_danger5)
				{
					echo '<div class="alert alert-danger aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>'.$alert_danger5.'.</div>';
				}
				alert_danger5($alert_danger5);
			}
		// end alert danger
			if (!empty($alert_redirect_back)) {				
				function redirectback($pageurgoingto , $seconds)
				{
					if (!isset($seconds))
					{
						$seconds = 3 ;
					}
					$pageurgoingto = $_SERVER['HTTP_REFERER'];
					echo '<div class="alert alert-info aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>You will be Redirect to <strong>'.$pageurgoingto.'</strong> after <strong> '.$seconds.' seconds</strong>.</div>';
					header("refresh: $seconds ; url= $pageurgoingto");
					exit();
				}
				redirectback($pageurgoingto,$seconds);	
			}
			elseif (!empty($alert_redirect_home))
			{
				function redirecthome($pageurgoingto , $seconds = 1)
				{
					if (!isset($seconds))
					{
						$seconds = 0 ;
					}
					$pageurgoingto = "http://localhost/ecommerce/admin/";
					echo '<div class="alert alert-info aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>You will be Redirect to <strong>Main Page</strong> after <strong> '.$seconds.' seconds</strong>.</div>';
					header("refresh: $seconds ; url= $pageurgoingto");
				}
				redirecthome($pageurgoingto,$seconds);	
				exit();
			}
			elseif(!empty($alert_redirect_custom) && !empty($place))
			{
				function redirectcustom($place , $seconds)
				{
					if (!isset($seconds)) {
						$seconds = 3 ; 
					}
						echo '<div class="alert alert-info aler-dismissible text-center"><button class="close hvr-icon-pulse-grow" data-dismiss="alert"><i class="fa fa-times hvr-icon"></i></button>You will be Redirect to <strong>'.$place.'</strong> after <strong> '.$seconds.' seconds</strong>.</div>';
					header("refresh: $seconds ; url= $place");
				}
				redirectcustom($place , $seconds);
			}

	}
//////////////////////////	Function Know if username exists or no 	/////////////////////////////
//////////////////////////	Very Very important function we need it alot	/////////////////////
//////////////////////////			Chick items function				/////////////////////////
	/*
		** Function to check items in database accept parameters
		** $select = The item to select (example) [User Catagory , item]	
		** $from = The table to select form [example : users ....]
		** $value = The value of $select [Example : username : osama ...]
	*/
////////////////////////			Use Parameters v 1.0				/////////////////////////
	function checkItem($select, $from , $value)
	{
		global $con; // global connect anywhere
		// select the item or name you want to check if exist or not
		$statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
		// execute the Value
		$statement ->execute(array($value));
		// the check resualt 1 true the name or item exist 0 not exist
		$count = $statement->rowCount();
		// echo the resault of the count
		return $count;

	}


	/* 
		**	Count Items function v1.0
		**	$item = the thing you wanna know its count
		**	$table = the table you search in
		** 	countItem()
	*/
	function countItem($item , $table)
	{
		global $con ;
		global $where; 
		$stm2 = $con->prepare("SELECT COUNT('$item') FROM $table $where");
		$stm2->execute();
		return $stm2->fetchColumn(); 
	}


	/*
	** 	Get latest items redords
	**	the items is
	**	userre comments items
	** $select the record u need
	** $table Select from
	** $limit the record Limit

	*/
	function getLatest($select, $table, $order,$limit , $where = null) {
		global $con;
		$getStmt = $con->prepare("SELECT $select FROM $table $where ORDER BY $order DESC LIMIT $limit");
		$getStmt->execute();
		$rows = $getStmt->fetchAll();
		return $rows ;
	}

//////// limit text function 

	function limit_text($text, $limit) {
      if (str_word_count($text, 0) > $limit) {
          $words = str_word_count($text, 2);
          $pos = array_keys($words);
          $text = substr($text, 0, $pos[$limit]) . '...';
      }
      return $text;
    }