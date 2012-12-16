<?php
include_once('includes/message/linkMessage.php');
class linkController extends appController
{
	function __construct()
	{
		parent::__construct();
		$this->view->view();
	}
	
	function admin_add()
	{
		$this->view->title=ADD_TITLE;
		if($this->link->validate())
			if($this->link->insert('blog_link',$this->link->data))
				$this->view->message=$this->message(LINK_ADD_SUCCESSFULY);
			else
				$this->view->message=$this->message(LINK_ADD_UNSUCCESSFUL,'red');	
	}
	
	function admin_edit()
	{
		$this->view->title=EDIT_TITLE;
		if($this->link->validate())
			if($this->link->update('blog_link',$this->link->data,ID))
				$this->view->message=$this->message(LINK_EDIT_SUCCESSFULY);
			else		
				$this->view->message=$this->message(LINK_EDIT_UNSUCCESSFUL,'red');
				
		$this->view->link=$this->link->select('blog_link')->where("id='".ID."'")->fetchOne();
		if(!$this->view->link)
			$this->view->message=$this->message(link_NOTFOUND,'yellow');		
	}

	function ensembleWork()
	{
		if(!is_null($this->link->data['ids']))
		{
			switch($this->link->data['title'])
			{
				case 'delete':
					# delete link
					foreach($this->link->data['ids'] as $key=>$value)
						if($this->link->delete('blog_link',$key))
							$this->view->message=$this->message(LINK_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(LINK_LIST_UNSUCCESSFUL,'red');	
				break;																																
			}
		}
		else
			$this->view->message=$this->message(LINK_LIST_SELECT,'yellow');
	}
	
	function admin_delete()
	{
		$this->view->title=DELETE_TITLE;
		if($this->link->delete('blog_link',ID))
			$this->view->message=$this->message(LINK_DELETE_SUCCESSFULY);
		else
			$this->view->message=$this->message(LINK_DELETE_UNSUCCESSFUL,'red');	
	}
	
	function admin_list()
	{
		$this->view->title=LIST_TITLE;
		if($this->link->validate())
			$this->ensembleWork();
		# view link list			
		$this->view->link=$this->link->select('blog_link')->order()->limit(ID*20,20)->fetchAll();
		$count=$this->link->select('blog_link','COUNT(id)')->fetchOne();
		$i=0;
		if($count['COUNT(id)']>=20)
			while($i*20<$count['COUNT(id)'])
				$page[$i]=$i++;	
		$this->view->page=$page;		
	}
}
?>