<?php
include_once('includes/message/categoryMessage.php');
class categoryController extends appController
{
	function __construct()
	{
		parent::__construct();
		$this->view->view();
	}
	
	function admin_add()
	{
		# add category
		if($this->category->validate())
			if($this->category->insert('blog_category',$this->category->data))
				$this->view->message=CATEGORY_ADD_SUCCESSFULY;
			else
				$this->view->message=CATEGORY_ADD_UNSUCCESSFULY;
	}
	
	function admin_edit()
	{
		# edit category
		if($this->category->validate())
			if($this->category->update('blog_category',$this->category->data,ID))
				$this->view->message=CATEGORY_EDIT_SUCCESSFULY;
			else
				$this->view->message=CATEGORY_EDIT_UNSUCCESSFULY;
		$this->view->category=$this->category->select('blog_category')->where('id=\''.ID."'")->fetchOne();							
	}
	
	function admin_delete()
	{
		# delete category
		if($this->category->delete('blog_post_category',ID,'category_id')===TRUE)
			if($this->category->delete('blog_category',ID)===TRUE)
				$this->view->message=$this->message(CATEGORY_DELETE_SUCCESSFULY);
			else
				$this->view->message=$this->message(CATEGORY_DELETE_UNSUCCESSFULY,'red');	
		else
			$this->view->message=$this->message(CATEGORY_DELETE_UNSUCCESSFULY,'red');					
	}
	
	function admin_list()
	{
		if($this->category->validate())
			$this->ensembleWork();
		# fetch categorys
		$this->view->category=$this->category->select('blog_category')->order()->limit(ID*20,20)->fetchAll();	
		#create pages
		$count=$this->category->select('blog_category','COUNT(id)')->fetchOne();
		$this->view->page=$this->pages($count['COUNT(id)'],20);	
	}
	
	function ensembleWork()
	{
		if(!is_null($this->category->data['ids']))
		{
			switch($this->category->data['title'])
			{
				case 'delete':
					# delete category
					foreach($this->category->data['ids'] as $key=>$value)
						if($this->category->delete('blog_post_category',$key)===TRUE)
							if($this->category->delete('blog_category',$key))
								$this->view->message=$this->message(CATEGORY_LIST_SUCCESSFUL);
							else
								$this->view->message=$this->message(CATEGORY_LIST_UNSUCCESSFUL);	
				break;																																
			}
		}
		else
			$this->view->message=$this->message(CATEGORY_LIST_SELECT,'yellow');
	}
	
	function view()
	{
		$query='SELECT * FROM `blog_post` AS `p` LEFT JOIN `blog_post_category` AS `c` ON c.post_id = p.id WHERE(c.category_id=\''.ID.'\') ORDER BY p.id';
		$this->view->post=$this->category->queryFetch($query)->fetchAll();	
	}
}
?>