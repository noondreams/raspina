<?php
class option extends appModel
{
	function __construct()
	{
		parent::__construct();
		$this->validate=array(
		'name'=>'data',
		'required'=>array('title','post_count'),
		'strip'=>array('title','slogan','description','meta'),
		'int'=>'post_count'
		);
	}
}
?>
