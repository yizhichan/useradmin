<?
$prefix		= 's_';
$dbId		= 'seller';
$configFile	= array( ConfigDir.'/Db/seller.master.config.php' );

$tbl['ad'] = array(
	'name'		=> $prefix.'ad',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);

$tbl['exchange'] = array(
	'name'		=> $prefix.'exchange',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);

$tbl['messege'] = array(
	'name'		=> $prefix.'messege',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);

$tbl['messegeMonitor'] = array(
	'name'		=> $prefix.'messege_monitor',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);

$tbl['messegeUser'] = array(
	'name'		=> $prefix.'messege_user',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);

$tbl['passSale'] = array(
	'name'		=> $prefix.'pass_sale',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);

$tbl['quotation'] = array(
	'name'		=> $prefix.'quotation',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);

$tbl['quotationItems'] = array(
	'name'		=> $prefix.'quotation_items',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);

$tbl['total'] = array(
	'name'		=> $prefix.'total',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);

$tbl['totalLog'] = array(
	'name'		=> $prefix.'total_log',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);

$tbl['userImage'] = array(
	'name'		=> $prefix.'user_image',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);


?>