<?php
class indexController extends appController
{
	public function __construct($controller,$action,$id,$role)
	{
		parent::__construct($controller,$action,$id,$role);
	}
	
	public function index()
	{
		$this->view->render=array();	
	}	
}