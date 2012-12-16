<?php
class upload extends appModel
{
	function __construct()
	{
		parent::__construct();
		if(VIEW=='list')
			$this->validate=array(
			'name'=>'data',
			'required'=>'title',
			);
		else		
			$this->validate=array(
			'name'=>'data',
			'required'=>'url',
			);
	}	
}
?>
