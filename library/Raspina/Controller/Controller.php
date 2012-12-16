<?php

class controller
{
	public $view;
	public $controller;
	public $action;
	public $id;
	public $role;	
	public function __construct()
	{
		if(strpos(FILE_NAME,'admin.php'))
			$view='_'.$this->action;
		else
			$view=$this->action;	

		if(strpos($view,'_')===0)
		{
			$this->authorize();
			if(!$this->authorize->userAuthorize())
				exit('no access.');
		}

		$this->model($this->controller);
		$this->view=new template($this->controller,$this->action);
	}

	function model($model)
	{
		if(!file_exists('application/model/'.$model.'.php'))
			exit($model.'.php (model) 404 not found!');

		require_once('application/model/appModel.php');
		require_once('application/model/'.$model.'.php');
		$this->$model=new $model;
	}

	function authorize()
	{
		require_once('include/classes/authorize.php');
		$this->authorize=new authorize;
	}
}