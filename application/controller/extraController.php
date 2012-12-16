<?php
include_once('includes/message/extraMessage.php');
class extraController extends appController
{
	function __construct()
	{
		parent::__construct();
		$this->view->view();
	}
	
	function admin_add()
	{
		# set username
		$this->view->username=$_SESSION['username'];
		# add extra
		if($this->extra->validate())
			if($this->extra->insert('blog_post',$this->extra->data))
				$this->view->message=$this->message(EXTRA_ADD_SUCCESSFULY);
			else
				$this->view->message=$this->message(EXTRA_ADD_UNSUCCESSFUL,'red');	
	}
	
	function admin_edit()
	{
		# update extra	
		if($this->extra->validate())
			if($this->extra->update('blog_post',$this->extra->data,ID))
				$this->view->message=$this->message(EXTRA_EDIT_SUCCESSFULY);
			else		
				$this->view->message=$this->message(EXTRA_EDIT_UNSUCCESSFUL,'red');
		# view extra
		$this->view->post=$this->extra->select('blog_post')->where('id=\''.ID.'\' AND post_extra=\'1\'')->fetchOne();
		if(!$this->view->post)
			$this->view->message=$this->message(EXTRA_NOTFOUND,'yellow');				
	}
	
	function admin_delete()
	{
		# delete extra
		if($this->extra->delete('blog_post',ID))
			$this->view->message=$this->message(EXTRA_DELETE_SUCCESSFULY);
		else
			$this->view->message=$this->message(EXTRA_DELETE_UNSUCCESSFUL,'red');	
	}
	
	function view()
	{
		# view extra
		$this->view->post=$this->extra->select('blog_post')->where('id=\''.ID.'\' AND post_static=\'1\'')->fetchOne();
		if(!$this->view->post)
			$this->view->message=EXTRA_NOTFOUND;
	}
	
	function admin_list()
	{
		if($this->extra->validate())
			$this->ensembleWork();		
		# view extra
		$this->view->post=$this->extra->
		select('blog_post','id,post_title,post_author,post_date')->
		where('post_extra=\'1\'')->order()->limit(ID*5,5)->fetchAll();
		#create pages
		$count=$this->extra->select('blog_post','COUNT(id)')->where('post_extra=\'1\'')->fetchOne();

		$i=0;
		if($count['COUNT(id)']>=5)
			while($i*5<$count['COUNT(id)'])
				$page[$i]=$i++;	
		$this->view->page=$page;		
	}
	
	function ensembleWork()
	{
		if(!is_null($this->extra->data['ids']))
		{
			switch($this->extra->data['title'])
			{
				case 'delete':
					# delete extra
					foreach($this->extra->data['ids'] as $key=>$value)
						if($this->extra->delete('blog_post',$key))
							$this->view->message=$this->message(EXTRA_LIST_SUCCESSFUL);
				break;																																		
			}
		}
		else
			$this->view->message=$this->message(EXTRA_LIST_SELECT,'yellow');		
	}	
}
?>