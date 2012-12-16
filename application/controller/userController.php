<?php
include_once('include/message/userMessage.php');
class userController extends appController
{
	function __construct()
	{
		parent::__construct();
		$this->view->view();
	}
	
	function login()
	{
		$this->appIndex();
		$this->authorize();
		if($this->user->validate())
			if($this->authorize->login($this->user->data))
				header('Location: '.URL.'admin');
			else
				$this->view->message=USER_LOGIN_UNSUCCESSFUL;
	}
	
	function logout()
	{
		session_start();
		session_destroy();
		session_unset();
	}
	
	function admin_edit()
	{
		if($this->user->validate())
		{
			# set permission
			if(array_key_exists('admin',$this->user->data['access']))
				$data['role']='admin';
			else	
				$data['role']=implode(',',$this->user->data['access']);
			$this->user->data=array_merge($this->user->data,$data);	
			unset($this->user->data['access']);
			# update user	
			if($this->user->update('blog_user',$this->user->data,ID))
				$this->view->message=$this->message(USER_EDIT_SUCCESSFULY);
				else
					$this->view->message=$this->message(USER_EDIT_UNSUCCESSFUL,'red');
		}
		
									
		$this->view->user=$this->user->select('blog_user')->where('`id`=\''.ID.'\'')->fetchOne();
		$this->view->role=explode(',',$this->view->user['role']);
	}
	
	function admin_list()
	{
		$this->view->user=$this->user->select('blog_user')->fetchAll();		
	}
	
	function admin_password()
	{	
		if($this->user->validate())
		{
			$userPassword=$this->user->select('blog_user','password')->where('`id`=\''.ID.'\'')->fetchOne();
			if($userPassword['password']==$this->user->data['pass1'] && $this->user->data['pass2']==$this->user->data['pass3'])
			{
				$data=array('password'=>$this->user->data['pass3']);
				if($this->user->update('blog_user',$data,ID))
					$this->view->message=$this->message(USER_PASS_SUCCESSFULY);
				else
					$this->view->message=$this->message(USER_PASS_UNSUCCESSFUL,'red');				
			}
				
		}
	}
	
	function admin_add()
	{
		if($this->user->validate())
		{
			# set permission
			if(array_key_exists('admin',$this->user->data['access']))
				$data['role']='admin';
			else	
				$data['role']=implode(',',$this->user->data['access']);
			unset($this->user->data['access']);	
			# insert user
			if($this->user->data['password']==$this->user->data['repassword'])
			{
				unset($this->user->data['repassword']);
				$this->user->data=array_merge($this->user->data,$data);
				if($this->user->insert('blog_user',$this->user->data))
					$this->view->message=$this->message(USER_ADD_SUCCESSFULY);
				else
					$this->view->message=$this->message(USER_ADD_UNSUCCESSFUL,'red');					
			}
			else
				$this->view->message=$this->message(USER_PASS_UNSUCCESSFUL,'red');	
		}
	}
	
	function admin_delete()
	{
		$user=$this->user->select('blog_user','role')->where('`id`=\''.ID.'\'')->fetchOne();
		if($user['role']!='admin')
			if($this->user->delete('blog_user',ID))
				$this->view->message=$this->message(USER_DELETE_SUCCESSFULY);
			else
				$this->view->message=$this->message(USER_DELETE_UNSUCCESSFULY,'red');
		else
			$this->view->message=$this->message(USER_ADMIN,'yellow');				
	}
}
?>