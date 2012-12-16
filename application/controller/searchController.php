<?php

class searchController extends appController
{
	function __construct()
	{
		parent::__construct();
		$this->view->view();
	}
	
	function view()
	{
		if($this->search->validate())
		{
			$queryString='SELECT `id`, `post_title` FROM blog_post WHERE(`post_title` LIKE \'%'.$this->search->data['search'].'%\' or `post_short_content` LIKE \'%'.$this->search->data['search'].'%\' or `post_full_content` LIKE \'%'.$this->search->data['search'].'%\')';
			$this->view->post=$this->search->queryFetch($queryString)->order()->fetchAll();
		}
	}
}
?>