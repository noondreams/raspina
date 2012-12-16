<?php
class search extends appModel
{
	function __construct()
	{
		parent::__construct();
		$this->validate=array(
		'name'=>'data',
		'required'=>'search',
		'strip'=>'search'
		);
	}	
}
?>
