<?php
class validate
{
	public $data;
	public function __construct($data)
	{
		$this->data=$data;
	}
	
	public function __destruct()
	{
		
	}
	
	public function getData()
	{
		return $this->data;	
	}
	
	public function latinChar($key)
	{
		$pattern = '/^[a-z0-9]*$/';
		if(!preg_match($pattern,trim(strtolower($this->data[$key]))))
			return false;
		return true;	
	}
	
	public function unicodeChar($key)
	{	
		$pattern = '/^([\p{Arabic}]|\s)*$/u';
		if(!preg_match($pattern,trim(strtolower($this->data[$key]))))
			return false;
		return true;    
	} 
	
	public function email($key)
	{	
		$pattern ="/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/";
		if(!preg_match($pattern,trim(strtolower($this->data[$key]))))
			return false;
		return true;	
	}
	
	public function number($key)
	{
		if(!is_numeric($this->data[$key]))
			return false;
		return true;		
	}
	
	public function required($key)
	{
		if(!strlen(trim($this->data[$key])))
			return false;
		return true;
	}
	
	public function escapeString($key)
	{
		if(is_null($this->data[$key]))
			return true;		
		$this->data[$key]=mysql_real_escape_string($this->data[$key]);
		return true;	
	}
	
	public function tolower($key)
	{
		if(is_null($this->data[$key]))
			return true;		
		$this->data[$key]=strtolower($this->data[$key]);
		return true;			
	}
	
	public function stripTag($key)
	{
		if(is_null($this->data[$key]))
			return true;
		$this->data[$key]=strip_tags($this->data[$key]);
		return true;			
	}
	
	public function pDate($key)
	{
		require_once 'library'.DS.'Raspina'.DS.'Date'.DS.'Pdate.php';
		$this->data[$key]=pdate('d / m / Y');
		return true;
	}
	
	public function pTime($key)
	{
		require_once 'library'.DS.'Raspina'.DS.'Date'.DS.'Pdate.php';
		$this->data[$key]=pdate('H:i a');	
		return true;	
	}

	public function encrypt($key)
	{
		$this->data[$key]=md5(sha1($this->data[$input]));	
		return true;				
	}
}
