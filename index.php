<?php
define('FILE_NAME', __FILE__);


require_once 'library/Raspina/Init/Initialize.php';
require_once 'library'.DS.'Raspina'.DS.'Registry'.DS.'Registry.php';
$registry=new registry();

# set controller and action 
if(isset($_REQUEST['action']))
{
	$param=explode('/',$_REQUEST['action']);
	$count=count($param)-1;
	for($i=0;$i<=$count;$i++)
	{
		$param[$i]=mysql_real_escape_string(strip_tags($param[$i]));
		$registry->$i=$param[$i];
	}
	
	
}



define('LIBRARYDIR', __DIR__ . DIRECTORY_SEPARATOR . 'Library' . DIRECTORY_SEPARATOR);
define('PHPEXT', '.php');
// start class loding
function __autoload($className)
{
	// echo 'ehsan: '.$className;
    $classFilePath = LIBRARYDIR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . PHPEXT;
    if(!file_exists($classFilePath))
        throw new Exception("'{$classFilePath}' class file not find");
    
    require_once $classFilePath;
}

require_once 'library'.DS.'Raspina'.DS.'Db'.DS.'Ex.Requirements.php';

require_once 'library'.DS.'Raspina'.DS.'Model'.DS.'Model.php';
require_once 'library'.DS.'Raspina'.DS.'Route'.DS.'Route.php';
require_once 'library'.DS.'Raspina'.DS.'Controller'.DS.'Controller.php';
require_once 'library'.DS.'Raspina'.DS.'Template'.DS.'Template.php';
require_once 'application'.DS.'controller'.DS.'appController.php';

$registry->router = new router($registry);