<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 16.05.2016
 * Time: 14:15
 */
define('IN_SITE', true);

// SET PHP ENVIRONMENT
@error_reporting(E_ALL & ~E_NOTICE);
@ini_set('error_reporting', E_ALL & ~E_NOTICE);
@ini_set('display_errors', '1');
@ini_set('display_startup_errors', '0');
@ini_set('ignore_repeated_errors', '1');
@session_start();
date_default_timezone_set('Europe/Moscow');
date_default_timezone_set(date_default_timezone_get());
/**
 * Full path to releaser sources
 * @var string
 */
define ('ROOT_PATH', str_replace("include","",dirname(__FILE__)));

require_once(ROOT_PATH . 'include/classes.php');
require_once(ROOT_PATH . 'include/functions.php');
require_once(ROOT_PATH . 'include/blocks.php');
require_once(ROOT_PATH . 'include/secrets.php');
// кэширование
require_once(ROOT_PATH . 'classes/cache/cache.class.php');
$REL_CACHE=new Cache();
if (REL_CACHEDRIVER=='native') {
    require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
    $REL_CACHE->addDriver(NULL, new FileCacheDriver());
}
elseif (REL_CACHEDRIVER=='memcached') {
    require_once(ROOT_PATH .  'classes/cache/MemCacheDriver.class.php');
    $REL_CACHE->addDriver(NULL, new MemCacheDriver());
}

$tstart = microtime(true); // Start time



ajaxcheck();

?>
