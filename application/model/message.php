<?php
class message extends appModel
{
	function __construct()
	{
		$this->validate=array(
		'required'=>array('mail','message'),
		'email'=>'mail',
		'stripTag'=>array('name','mail','message'),
		'escapeString'=>array('name','mail','message')
		);
		parent::__construct();	
	}	
}
?>
