<?php
class optionController extends appController
{
	function __construct()
	{
		parent::__construct();
		$this->view->view();
	}
	
	function admin_edit()
	{
		if($this->option->validate())
			if($this->option->update('blog_option',$this->option->data,0))
				$this->view->message=$this->message('UPDATE');
			else
				$this->view->message=$this->message('NOT UPDATE','red');	

		$this->view->option=$this->option->select('blog_option')->fetchOne();
	}
}
?>