<?
//Memcache数据缓存
$configs[] = array(
'id'        => 'mem',
'enable'    => true,
'source'    => LibDir.'/Util/Cache/MmCache.php',
'className' => 'MmCache',
'import'    => array(LibDir.'/Util/Cache/ICache.php'),
'property'  => array(
    'expire'     => 1800,
	'configFile' => ConfigDir.'/memcache.config.php',
	'objRef'	 => array('encoding' => 'encoding'),
));

//全文索引客户端代理
$configs[] = array(
'id'        => 'sphinx',
'enable'    => true,
'source'    => LibDir.'/Util/Tool/SphinxClientProxy.php',
'className' => 'SphinxClientProxy',
'property'  => array(
	'objRef' => array('sphinxClient' => 'sphinxClient'),
));

//全文索引客户端
$configs[] = array(
'id'        => 'sphinxClient',
'enable'    => true,
'source'    => LibDir.'/Util/Tool/SphinxClient.php',
'className' => 'SphinxClient',
'property'  => array(
	'_host'  => '127.0.0.1',
	'_port'  => 9312,
	'_arrayresult' => true,
	'_timeout' => 1,
));


//Redis数据缓存（DB-entity 在使用）
$configs[] = array(
'id'        => 'redis',
'enable'    => true,
'source'    => LibDir.'/Util/Cache/RedisCache.php',
'className' => 'RedisCache',
'import'    => array(LibDir.'/Util/Cache/ICache.php'),
'property'  => array(
    'expire'     => 1800,
    'configFile' => ConfigDir.'/redis.config.php',
    'objRef'     => array('encoding' => 'encoding'),
));

//Redis数据缓存（页面action缓存或其他 在使用）
$configs[] = array(
'id'        => 'redisHtml',
'enable'    => true,
'source'    => LibDir.'/Util/Cache/RedisCache.php',
'className' => 'RedisCache',
'import'    => array(LibDir.'/Util/Cache/ICache.php'),
'property'  => array(
    'expire'     => 1800,
    'configFile' => ConfigDir.'/redishtml.config.php',
    'objRef'     => array('encoding' => 'encoding'),
));

//redis管理、清除缓存
$configs[] = array(
'id'        => 'qcache',
'enable'    => true,
'source'    => LibDir.'/Util/Structure/RedisManager.php',
'className' => 'RedisManager',
'property'   => array(
    'configFile' => ConfigDir.'/qcache.redis.config.php',
));

//Redis 队列键值操作
$configs[] = array(
'id'        => 'redisQc',
'enable'    => true,
'source'    => LibDir.'/Util/Cache/RedisCache.php',
'className' => 'RedisCache',
'import'    => array(LibDir.'/Util/Cache/ICache.php'),
'property'  => array(
    'expire'     => 1800,
    'configFile' => ConfigDir.'/redisq.config.php',
    'objRef'     => array('encoding' => 'encoding'),
));

//Redis队列操作(队列配置，勿清)
$configs[] = array(
'id'        => 'redisQ',
'enable'    => true,
'source'    => LibDir.'/Util/Queue/RedisQ.php', 
'className' => 'redisQ',
'import'    => array(LibDir.'/Util/Queue/IQueue.php'),
'property'  => array(
    'expire'     => 1800,
    'configFile' => ConfigDir.'/redisq.config.php',
    'objRef'     => array('encoding' => 'encoding'),
));



?>