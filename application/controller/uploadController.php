<?php
include_once('includes/message/uploadMessage.php');
class uploadController extends appController
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
			# uploda file
			$path='includes/upload/';
			$path=$path.basename($_FILES['file']['name']);
			move_uploaded_file($_FILES['file']['tmp_name'],$path);
		}
	}
	
	function admin_exist()
	{
		# set path
		$src='includes/upload/'.$_POST['name'];
		# check file exist
		if(file_exists($src))
			$this->view->message=$this->message(UPLOAD_FOUND);
		else
			$this->view->message=$this->message(UPLOAD_NOT_FOUND,'red');	
	}
	
	function admin_delete()
	{
		# delete file
		if(unlink('includes/upload/'.$_POST['fileName']))
			$this->view->message=$this->message(UPLOAD_DELETE_SUCCESSFULY);
		else
			$this->view->message=$this->message(UPLOAD_DELETE_UNSUCCESSFULY,'red');	
	}
	
	function admin_list()
	{
		if($this->upload->validate())
			$this->ensembleWork();
		# set dir	
		$dir='includes/upload/';
		# open dir and read all files 
		if($open=opendir($dir))
		{
			while($file=readdir($open))
				$fileName[]=array('name'=>$file,'size'=>filesize('includes/upload/'.$file));
		}
		else
			$this->view->message=$this->message(DIRECTORY_NOTFOUND,'red');
		# sort files
		unset($fileName[0]);
		unset($fileName[1]);	
		if(!is_null($fileName))
			sort($fileName);
		# view files
		$this->view->files=$fileName;
	}

	function ensembleWork()
	{
		if(!is_null($this->upload->data['ids']))
		{
			switch($this->upload->data['title'])
			{
				case 'delete':
					# delete file
					foreach($this->upload->data['ids'] as $key=>$value)
						if(unlink('includes/upload/'.$key))
							$this->view->message=$this->message(UPLOAD_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(UPLOAD_LIST_UNSUCCESSFUL,'red');	
				break;																																
			}
		}
		else
			$this->view->message=$this->message(UPLOAD_LIST_SELECT,'yellow');
	}	
}
?>