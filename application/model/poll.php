<?php
class poll extends appModel
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
			'required'=>array('title','text'),
			'strip'=>array('title','text'),
			'date'=>'date',
			);
	}	
}
?>
