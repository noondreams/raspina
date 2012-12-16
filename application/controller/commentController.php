<?php
include_once('includes/message/commentMessage.php');
class commentController extends appController
{
	function __construct()
	{
		parent::__construct();
		$this->view->view();
	}
	
	function add()
	{
		$this->view->title=ADD_TITLE;
		# add comment
		if($this->comment->validate())
		{
			$this->comment->data['post_id']=ID;
			$comment_count=$this->comment->select('blog_post','post_comment_count')->where("id='".ID."'")->fetchOne();
			$comment_count['post_comment_count']++;
			if($this->comment->insert('blog_comment',$this->comment->data) && $this->comment->update('blog_post',$comment_count,ID))
				$this->view->message=COMMENT_ADD_SUCCESSFULY;
			else
				$this->view->message=COMMENT_ADD_UNSUCCESSFULY;	
		}
	}
	
	function admin_delete()
	{
		# delete comment
		if($this->comment->delete('blog_comment',ID)===TRUE)
				$this->view->message=$this->message(COMMENT_DELETE_SUCCESSFULY);
			else
				$this->view->message=$this->message(COMMENT_DELETE_UNSUCCESSFULY,'red');
	}
	
	function admin_list()
	{
		$this->view->title=LIST_TITLE;
		if($this->comment->validate())
			$this->ensembleWork();
		# view comment list 		
		switch(RULE)
		{
			case 'all':
			   $this->view->comment=$this->comment->select('blog_comment')->where('`message`=0')->order()->limit(ID*20,20)->fetchAll();
			   $count=$this->comment->select('blog_comment','COUNT(id)')->where('`message`=0')->fetchOne();	
			   break;	
			case 'grace':
			   $this->view->comment=$this->comment->select('blog_comment')->where('`grace`=1 AND `message`=0')->order()->limit(ID*20,20)->fetchAll();
			   $count=$this->comment->select('blog_comment','COUNT(id)')->where('`grace`=1 AND `message`=0')->fetchOne();	
			   break;
			case 'ungrace':
			   $this->view->comment=$this->comment->select('blog_comment')->where('`grace`=0 AND `message`=0')->order()->limit(ID*20,20)->fetchAll();
			   $count=$this->comment->select('blog_comment','COUNT(id)')->where('`grace`=0 AND `message`=0')->fetchOne();	
			   break;
			case 'hidden':
			   $this->view->comment=$this->comment->select('blog_comment')->where('`hidden`=1 AND `message`=0')->order()->limit(ID*20,20)->fetchAll();
			   $count=$this->comment->select('blog_comment','COUNT(id)')->where('`hidden`=1 `message`=0')->fetchOne();	
			   break;
			case 'show':
			   $this->view->comment=$this->comment->select('blog_comment')->where('`hidden`=0 `message`=0')->order()->limit(ID*20,20)->fetchAll();
			   $count=$this->comment->select('blog_comment','COUNT(id)')->where('`hidden`=0 `message`=0')->fetchOne();	
			   break;
			default:
			   $this->view->comment=$this->comment->
			   select('blog_comment')->where('`message`=0')->order()->limit(ID*20,20)->fetchAll();
			   $count=$this->comment->select('blog_comment','COUNT(id)')->where('`message`=0')->fetchOne();		   
		}
		$i=0;
		if($count['COUNT(id)']>=20)
			while($i*20<$count['COUNT(id)'])
				$page[$i]=$i++;	
		$this->view->page=$page;		
	}
	
	function ensembleWork()
	{
		if(!is_null($this->comment->data['ids']))
		{
			switch($this->comment->data['title'])
			{
				case 'grace':
					# grace comment
					foreach($this->comment->data['ids'] as $key=>$value)
						if($this->comment->queryExec("UPDATE `blog_comment` SET `grace`='1' WHERE(`id`='$key')"))
							$this->view->message=$this->message(COMMENT_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(COMMENT_LIST_UNSUCCESSFUL,'red');	
				break;	
				case 'ungrace':
					# un grase comment
					foreach($this->comment->data['ids'] as $key=>$value)
						if($this->comment->queryExec("UPDATE `blog_comment` SET `grace`='0' WHERE(`id`='$key')"))
							$this->view->message=$this->message(COMMENT_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(COMMENT_LIST_UNSUCCESSFUL,'red');	
				break;
				case 'delete':
					# delete comment
					foreach($this->comment->data['ids'] as $key=>$value)
						if($this->comment->delete('blog_comment',$key))
							$this->view->message=$this->message(COMMENT_LIST_SUCCESSFUL);
				break;
				case 'hide':
					# hidden comment
					foreach($this->comment->data['ids'] as $key=>$value)
						if($this->comment->queryExec("UPDATE `blog_comment` SET `hidden`='1' WHERE(`id`='$key')"))
							$this->view->message=$this->message(COMMENT_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(COMMENT_LIST_UNSUCCESSFUL,'red');
				break;	
				case 'show':
					# show comment
					foreach($this->comment->data['ids'] as $key=>$value)
						if($this->comment->queryExec("UPDATE `blog_comment` SET `hidden`='0' WHERE(`id`='$key')"))
							$this->view->message=$this->message(COMMENT_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(COMMENT_LIST_UNSUCCESSFUL,'red');
				break;																																	
			}
		}
		else
			$this->view->message=$this->message(COMMENT_LIST_SELECT,'yellow');		
	}
	
	function admin_grace()
	{
		$this->view->title=GRACE_TITLE;
		# grace comment
		$data=array('status'=>'1');
		if($this->comment->update('blog_comment',$data,ID))
			$this->view->message=COMMENT_GRACE_SUCCESSFULY;
		else
			$this->view->message=COMMENT_GRACE_UNSUCCESSFULY;
	}
	
	function admin_response()
	{
		$this->view->title=RESPONSE_TITLE;
		# response comment
		if($this->comment->validate())
			if($this->comment->update('blog_comment',$this->comment->data,ID))
				$this->view->message=COMMENT_RESPONSE_SUCCESSFULY;
			else
				$this->view->message=COMMENT_RESPONSE_UNSUCCESSFULY;
		$response_text=$this->comment->select('blog_comment','response')->where("id='".ID."'")->fetchOne();
		$this->view->response=$response_text['response'];				
	}
}
?>