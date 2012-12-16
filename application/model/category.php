<?php
class category extends appModel
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
			'required'=>'name',
			'strip'=>'name',
			'lrtrim'=>'name'
			);			
	}	
}
?>
