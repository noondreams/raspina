<?php
class link extends appModel
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
			'required'=>array('url','text'),
			'strip'=>array('url','text','description')
			);
	}	
}
?>
