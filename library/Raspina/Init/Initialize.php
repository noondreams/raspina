<?php
error_reporting(E_ALL);
ini_set('ignore_repeated_errors', 1);

if(version_compare(PHP_VERSION, '5', 'lt'))
{
	exit('PHP +5 required.');
}

@set_time_limit(0);
@set_magic_quotes_runtime(0);

ini_set('max_execution_time', 0);
@ini_set('magic_quotes_gpc', 0);
@ini_set('magic_quotes_runtime', 0);
ini_set('zend.ze1_compatibility_mode', 0);
@ini_set('register_globals', 0);
ini_set('mbstring.func_overload', 0); # http://bugs.php.net/bug.php?id=30766
ini_set('session.use_trans_sid', 0);

$_REQUEST = array_merge($_GET, $_POST); # !$_COOKIE
define('DS', (DIRECTORY_SEPARATOR == '/') ? '/' : '\\\\');
