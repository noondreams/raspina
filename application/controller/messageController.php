<?php
class messageController extends appController
{
	public function __construct($controller,$action,$id,$role)
	{
		parent::__construct($controller,$action,$id,$role);
	}
	
	public function add()
	{
		# view 
		$this->view->render=array();
		
		if($this->message->validate())
		{
				$create = array(
					'table'=>'message',
					'values'=>$this->message->data
				);
				$this->message->create($create);
		}		
	}
	
	public function view()
	{
		$this->message->query->set("SELECT * FROM `message` ORDER BY `id` DESC LIMIT 0,10");
		$this->message->execute();
		$results =$this->message->fetchAll();
		# view 
		$this->view->render=array('results'=>$results);		
	}
	
	public function delete()
	{
		$delete = array(
			'table'=>'message',
			'where'=>array('id', $this->id)
			);
		$this->message->query->delete($delete);
		$this->message->execute();
		$this->view->render=array('msg'=>'delete message');	
	}

	public function edit()
	{
		#update message
		if($this->message->validate())
		{
			$edit = array(
				'table'=>'message',
				'update'=>$this->message->data,
				'where'=>array('id', $this->id)
				);
			$this->message->edit($edit);
		}
		# read message
		$read = array(
			'table'=>'message',
			'cols'=>'all',
			'where'=>array('id', $this->id)
			);
		$this->message->query->set("SELECT * FROM `message` WHERE(`id`=$this->id)");
		$this->message->execute();
		$result=$this->message->fetch();
		$this->view->render=array('result'=>$result);		
	}	
}
?>