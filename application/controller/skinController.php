<?php
include_once('includes/message/skinMessage.php');
class skinController extends appController
{
	function __construct()
	{
		parent::__construct();
		$this->view->view();
	}

	function admin_add()
	{
		# if send data or true submit form
		if(strlen($_FILES['file']['name']))
		{
			# uploda skin
			$path='includes/template/blog/';
			$path=$path.basename($_FILES['file']['name']);
			# if .zip
			if($_FILES['file']['type']=='application/zip')
				if(move_uploaded_file($_FILES['file']['tmp_name'],$path))
				{
					$zip=new ZipArchive;
					if($zip->open('includes/template/blog/'.$_FILES['file']['name'])===TRUE)
					{
						$zip->extractTo('includes/template/blog/');
						$zip->close();	
						unlink('includes/template/blog/'.$_FILES['file']['name']);
					}
				}
		}
	}
	
	function admin_exist()
	{
		$name=str_replace('.zip',NULL,$_POST['name']);
		# set path
		$src='includes/template/blog/'.$name;
		# check dir exist
		if(is_dir($src))
			$this->view->message=$this->message(SKIN_FOUND);
		else
			$this->view->message=$this->message(SKIN_NOT_FOUND,'red');	
	}
	
	function admin_delete()
	{
		# delete skin
		if($this->removeDir('includes/template/blog/'.$_POST['fileName'],true))
			$this->view->message=$this->message(SKIN_DELETE_SUCCESSFULY);
		else
			$this->view->message=$this->message(SKIN_DELETE_UNSUCCESSFULY,'red');
	}
	
	function admin_list()
	{
		if($this->skin->validate())
			$this->ensembleWork();
		# set dir	
		$dir='includes/template/blog/';
		# open dir and read all files 
		$dirContent=scandir($dir);
		# sort files
		unset($dirContent[0]);
		unset($dirContent[1]);	
		if(!is_null($dirContent))
			sort($dirContent);
		# view files
		$this->view->dir=$dirContent;
	}

	function ensembleWork()
	{
		if(!is_null($this->skin->data['ids']))
		{
			switch($this->skin->data['title'])
			{
				case 'delete':
					# delete file
					foreach($this->skin->data['ids'] as $key=>$value)
						if($this->removeDir('includes/template/blog/'.$key,true))
							$this->view->message=$this->message(SKIN_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(SKIN_LIST_UNSUCCESSFUL,'red');
				break;																																
			}
		}
		else
			$this->view->message=$this->message(SKIN_LIST_SELECT,'yellow');
	}
	
	function removeDir($dir, $DeleteMe)
	{
		# remove dir
		if(!$dh = @opendir($dir)) return;
		while (false !== ($obj = readdir($dh)))
		{
			if($obj=='.' || $obj=='..') continue;
			if (!@unlink($dir.'/'.$obj)) $this->removeDir($dir.'/'.$obj, true);
		}
		closedir($dh);
		if ($DeleteMe)
		{
			@rmdir($dir);
			return(TRUE);
		}
	}		
}
?>