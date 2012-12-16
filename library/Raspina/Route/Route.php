<?php
class router 
{
	private $registry;
	private $controller='index';
	private $action='index';
	private $id=0;
	private $role=0;
	public function __construct($registry)
	{	
        $this->registry=$registry;
		
		$controller=0;
		$action=1;
		$id=2;
		$role=3;
		if(isset($this->registry->$controller))
			$this->getController($this->registry->$controller);	
		if(isset($this->registry->$action))	
			$this->getAction($this->registry->$action);
		if(isset($this->registry->$id))	
			$this->getId($this->registry->$id);	
		if(isset($this->registry->$role))	
			$this->getRole($this->registry->$role);						
		
		$_controller = 'application/controller/'.$this->controller.'Controller.php';

		if(!is_file($_controller))
		{
			$this->Issue($this->controller.' (controller) 404 not found!');
		}

		if(!is_readable($_controller))
		{
			$this->Issue($this->controller.' (controller) is not valid.');
		}

		$app=new appController($this->controller,$this->action,$this->id,$this->role);
		$app->action($this->controller,$this->action,$this->id,$this->role);
	
		return;
	}

	public function __destruct()
	{
		return;
	}

	private function Issue($message)
	{
		exit($message);
		return;
	}

	public function getController($c)
	{
		$this->controller=$c;
	}
	
	public function getAction($a)
	{
		$this->action=$a;
	}
	
	public function getId($i)
	{
		$this->id=$i;
	}
	
	public function getRole($r)
	{
		$this->role=$r;		
	}
}