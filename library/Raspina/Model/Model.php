<?php
class model extends Raspina\Db\Db
{
	public $validate;
	public $data;
	function __construct()
	{
		$connection = array(
		'hostname'=>'localhost',
		'username'=>'root',
		'password'=>'',
		'database'=>'raspina',
		'driver'=>'Mysqli',
		);
		parent::__construct($connection);
	}
	
	function validate()
	{
		$returnFalse=0;
		if(count($_REQUEST))
		{		
			foreach($_REQUEST as $key=>$value)
				if(is_array($value))
					$this->data=$value;
			require_once 'library'.DS.'Raspina'.DS.'Model'.DS.'Validate.php';
			$validation=new validate($this->data);
						
			foreach($this->validate as $key=>$value)
			{
				$false=0;
				if(is_array($value))
				{
					$i=0;
					foreach($value as $item)
						if(!$validation->$key($value[$i++]))
							$false++;
							
					if($false)
						$returnFalse++;										
				}
				else
				{
					if(!$validation->$key($value))
						$returnFalse++;
				}
			}
			if($returnFalse)
				return false;
			$this->data=$validation->getData();
			return true;
		}
		return false;
	}
}
?>