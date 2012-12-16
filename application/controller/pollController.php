<?php
include_once('includes/message/pollMessage.php');
class pollController extends appController
{
	function __construct()
	{
		parent::__construct();
		$this->view->view();
	}
	
	function admin_add()
	{
		if($this->poll->validate())
		{
			# replace char (`) in answer 
			$answer=str_replace('`',NULL,$this->poll->data['text']);
			$count=strlen($answer)-1;
			# severance answers
			for($i=0;$i<=$count;$i++)
			{
				if(ord($answer[$i])==13 or ord($answer[$i])==10)
					$answer[$i]='`';
			}
			$exp=explode('`',$answer);

			for($i=0;$i<=$e_count;$i++)
				if(ord($exp[$i])==0)
					unset($exp[$i]);
			# create HTML struct poll	
			$text='<table width="100%" border="0" cellspacing="0" cellpadding="0">';
			$count=count($exp);
			for($i=0;$i<$count;$i++)
			{
				if($this->poll->data['input_select']!=1)
					$text.='<tr><td width="2%"><input type="radio" name="r['.($i+1).']" value="'.($i+1).'"/></td><td width="98%">'.($i+1).': '.$exp[$i].'</td></tr>';
				else
					$text.='<tr><td width="2%"><input type="checkbox" name="r['.($i+1).']" value="'.($i+1).'"/></td><td width="98%">'.($i+1).': '.$exp[$i].'</td></tr>';
			}
			$text.='</table>';
			$this->poll->data['text']=$text;
			# insert poll
			if($this->poll->insert('blog_poll',$this->poll->data))
				$this->view->message=$this->message(POLL_ADD_SUCCESSFULLY);
			else
				$this->view->message=$this->message(POLL_ADD_UNSUCCESSFULLY,'red');	
		}
	}
	
	function view()
	{
		# if send data
		if(isset($_POST['send']))
		{
			$poll_id='poll_id'.ID;
			# if not set cookie 
			if($_COOKIE[$poll_id]!=ID)
			{
				# select user ip 
				$ip=$this->poll->select('blog_poll_result','ip')->
				where('ip=\''.$_SERVER['REMOTE_ADDR'].'\' AND poll_id=\''.ID.'\'')->fetchOne();
				# if not found ip
				if(strlen($ip['ip'])<1 or TRUE)
				{
					# insert user vote
					$r=$_POST['r'];
					foreach($r as $k=>$v)
					{
						$data=array(
						'poll_id'=>ID,
						'ip'=>$_SERVER['REMOTE_ADDR'],
						'answer'=>$v
						);
						$this->poll->insert('blog_poll_result',$data);
					}
					# set cookie sake 1 month
					setcookie($poll_id,ID,time()+2592000);
				}
			}
		}
		# select poll
		$this->view->poll=$this->poll->select('blog_poll')->where('id=\''.ID.'\'')->fetchOne();
		# select result
		$this->view->result=$this->poll->select('blog_poll_result','COUNT(`answer`) AS `count`,answer,SUM(`answer`) as `sum`')->where('poll_id=\''.ID.'\'')->group('answer')->fetchAll();
		# select or get all vote
		$this->view->sum=$this->poll->select('blog_poll_result','COUNT(`answer`) as `sum`')->where('poll_id=\''.ID.'\'')->fetchOne();
	}
	
	function admin_list()
	{
		# ensemble Work
		if($this->poll->validate())
			$this->ensembleWork();
		# create page	
		$this->view->poll=$this->poll->select('blog_poll')->order()->limit(ID*20,20)->fetchAll();
		$count=$this->poll->select('blog_link','COUNT(id)')->fetchOne();
		$i=0;
		if($count['COUNT(id)']>=20)
			while($i*20<$count['COUNT(id)'])
				$page[$i]=$i++;	
		$this->view->page=$page;		
	}

	function ensembleWork()
	{
		if(!is_null($this->poll->data['ids']))
		{
			switch($this->poll->data['title'])
			{
				case 'delete':
					# delete poll
					foreach($this->poll->data['ids'] as $key=>$value)
						if($this->poll->delete('blog_poll',$key))
						{	
							$this->poll->delete('blog_poll_result',ID,'poll_id');
							$this->view->message=$this->message(POLL_LIST_SUCCESSFUL);
						}
						else
							$this->view->message=$this->message(POLL_LIST_UNSUCCESSFUL,'red');	
				break;
				case 'clear':
					# clear vote poll
					foreach($this->poll->data['ids'] as $key=>$value)
						if($this->poll->delete('blog_poll_result',$key,'poll_id'))
							$this->view->message=$this->message(POLL_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(POLL_LIST_UNSUCCESSFUL);		
				break;
				case 'enable':
					# enable poll
					foreach($this->poll->data['ids'] as $key=>$value)
						if($this->poll->queryExec("UPDATE `blog_poll` SET `enable`='1' WHERE(`id`='$key')"))
							$this->view->message=$this->message(POLL_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(POLL_LIST_UNSUCCESSFUL);						
				break;
				case'disable':
					# disable poll
					foreach($this->poll->data['ids'] as $key=>$value)
						if($this->poll->queryExec("UPDATE `blog_poll` SET `enable`='0' WHERE(`id`='$key')"))
							$this->view->message=$this->message(POLL_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(POLL_LIST_UNSUCCESSFUL);					
				break;																																
			}
		}
		else
			$this->view->message=$this->message(POLL_LIST_SELECT,'yellow');
	}
	
	function admin_delete()
	{
		# delete poll
		if($this->poll->delete('blog_poll',ID)===TRUE)
		{
			$this->poll->delete('blog_poll_result',ID,'poll_id');
			$this->view->message=$this->message(POLL_DELETE_SUCCESSFULY);
		}
		else
			$this->view->message=$this->message(POLL_DELETE_UNSUCCESSFULY,'red');				
	}
			
}
?>