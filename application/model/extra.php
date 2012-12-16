<?php
class extra extends appModel
{
	function __construct()
	{
		if(VIEW=='list')
			$this->validate=array(
			'name'=>'data',
			'required'=>'title'
			);
		else	
			$this->validate=array(
			'name'=>'data',
			'required'=>'post_title',
			'date'=>'post_date',
			'time'=>'post_time'
			);
		parent::__construct();
	}	
}
?>
