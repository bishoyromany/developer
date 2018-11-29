<?php 
 function lang($phrase)
{
	static $lang =array(
		// titles words
		'DashBOARD'			=>	'Dashboard',
		'LOGIN'				=>	'login',
		'DEFAULTTitle'		=>	'Wellcome to our website',
		'MEMBERS'			=>	'Manage Members',
		'CATEGORIES'		=>	'Manage Categories',
		'ITEMST'			=>	'Manage Items',
		''		=>	'',
		// End titles words
		// navbar into dashboard
		'HOME' 				=> 'Home Page',
		'SECTIONS' 			=> 'Categories',
		'EDIT' 				=> 'Edit Profile',
		'SETTINGS' 			=> 'Settings',
		'LOGOUT' 			=> 'Logout',
		'ITEMS'				=>	'Items',
		'MEMBERS'			=>	'Members',
		'COMMENTS'			=>	'Comments',
		'STATISTICS'		=>	'Statistics',
		'LOGS'				=>	'logs',
		// End into dashboard
		// Dashbaord words
		'DASHBOARD'			=>	'Dashboard',
		'TOTALMEMBERS'		=>	'Total Members',
		'PENDINGMEMBERS'	=>	'Pending Members',
		'TOTALITEMS'		=>	'Total Added Items',
		'TOTALCOMMENTS'		=>	'Total Comments',
		'LATESTREGISTERED'	=>	'Latest 5 Registired Users',
		'LATESTITEMS'		=>	'Latest 5 Added Items',
		''		=>	'',
		''		=>	'',
		// END Dashbaord words
		// manage members title words
		'MANAGEMEMBERS'		=>	'Manage Members',
		'ID'				=>	'#ID',
		'USERNAME'			=>	'Username',
		'EMAIL'				=>	'Email',
		'FULLNAME'			=>	'Full Name',
		'REGDATE'			=>	'Registered Date',
		'CONTROL'			=>	'Control',
		// manage members buttons
		'NEWMEMBER'		=>	'New Member',
		'DELETE'		=>	'Delete',
		'EDIT'			=>	'Edit',
		'ACTIVATE'		=>	'Activate',
		'DEACTIVATE'	=>	'DeActive',
		''		=>	'',
		// End manage members title words
		// start The Edit form 
		'EDITMEMBER'	=>	'Edit Member',
		'USERNAME'		=>	'Username',
		'PASSWORD'		=>	'Password',
		'EMAIL'			=>	'Email',
		'FULLNAME'		=>	'Full Name',
		'SAVE'			=>	'Save',
		''		=>	'',
		''		=>	'',
		// End The Edit form 
		// start The Add member form 
		'ADDNEWMEMBER'	=>	'Add New Member',
		'USERNAME'		=>	'Username',
		'PASSWORD'		=>	'Password',
		'EMAIL'			=>	'Email',
		'FULLNAME'		=>	'Full Name',
		'ADD'			=>	'Add',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		// End The Edit form 
		// start the add category form
		'NAME'			=>	'Name',
		'DESCRIPTION'	=>	'Description',
		'ORDRING'		=>	'Ordring',
		'VISIBILITY'	=>	'Visibility',
		'ALLOW_COMMENTS'=>	'Allow Comments',
		'ALLOW_ADS'		=>	'Allow Ads',
		'UPDATE'		=>	'Update',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	''
				);

	
	return $lang[$phrase];
};

