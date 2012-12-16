<?php
class comment extends appModel
{
	function __construct()
	{
		parent::__construct();
		if(VIEW=='response')
			$this->validate=array(
			'name'=>'data',
			'strip'=>'response'
			);
		elseif(VIEW=='add')
			$this->validate=array(
			'name'=>'data',
			'required'=>array('mail','comment','post_id'),
			'mail'=>'mail',
			'strip'=>array('name','mail','site','comment')
			);
		else
			$this->validate=array(
			'name'=>'data',
			'required'=>'title',
			);
	}	
}
?>
