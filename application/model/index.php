<?php
class index extends appModel
{
	function __construct()
	{
		$this->validate=array(
		'required'=>'name',
		'unicodeChar'=>'family'
		);		
		parent::__construct();	
	}
}