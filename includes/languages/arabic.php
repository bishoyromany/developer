<?php 
function lang($phrase)
{
	static $lang =array(
		// dash board site
		'HOME' => 'Home Page',
		'SECTIONS' => 'Categories',
		'EDIT' => 'Edit Profile',
		'SETTINGS' => 'Settings',
		'LOGOUT' => 'Logout',
		'ITEMS'		=>	'Items',
		'MEMBERS'	=>	'members',
		'STATISTICS'		=>	'Statistics',
		'LOGS'		=>	'logs',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
		''		=>	'',
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
