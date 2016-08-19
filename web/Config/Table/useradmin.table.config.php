<?
$prefix		= 'ua_';
$dbId		= 'useradmin';
$configFile	= array( ConfigDir.'/Db/useradmin.master.config.php' );

$tbl['adminRole'] = array(
	'name'		=> $prefix.'admin_role',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);

$tbl['adminUser'] = array(
	'name'		=> $prefix.'admin_user',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);

$tbl['userAddition'] = array(
	'name'		=> $prefix.'user_addition',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);


?>