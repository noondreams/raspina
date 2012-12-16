<?php
include_once('includes/message/postMessage.php');
class postController extends appController
{
	function __construct()
	{
		parent::__construct();
		$this->view->view();
		
	}
	
	function admin_add()
	{
		$this->view->title=ADD_TITLE;
		# set username
		$this->view->username=$_SESSION['username'];
		# fetch catgory
		$this->view->category=$this->post->select('blog_category','id,name')->order()->fetchAll();

		if($this->post->validate())
		{
			# var
			$category=$this->post->data['category'];
			unset($this->post->data['category']);	
			$tags=$this->post->data['tags'];
			$tags=array_unique(explode(',',$tags));
			unset($this->post->data['tags']);
			# if post_full_content exist
			if(strlen($this->post->data['post_full_content']))
			{
				$continue=array('post_continue'=>'1');
				$this->post->data=array_merge($this->post->data,$continue);
			}
	
			# insert post 
			if($this->post->insert('blog_post',$this->post->data))
			{
				$this->view->message=$this->message(POST_ADD_SUCCESSFULY);
				$postId=$this->post->select('blog_post','id,post_date')->order()->limit(0,1)->fetchOne();
				$date=explode(' / ',$postId['post_date']);
				$postId=$postId['id'];
				# insert archive
				$archive=array('post_id'=>$postId,'year'=>$date[2],'month'=>$date[1]);
				$this->post->insert('blog_archive',$archive);	
				# insert tags
				if(count($tags)>=1 && strlen($tags[0]))
				{
					$queryString='INSERT INTO `blog_tag` (`id`,`post_id`,`name`) VALUES ';
					foreach($tags as $tag)
						if(strlen(ltrim(rtrim($tag)))>0)
							$queryString.="(NULL,'$postId','$tag'),";
					$queryString[strlen($queryString)-1]='';
					if(!$this->post->queryExec($queryString))
						$this->view->tagMessage=$this->message(POST_TAGS_UNSUCCESSFUL,'yellow');
				}
				
				# insert categorys
				if(gettype($category)!='NULL')
				{
					$queryString='INSERT INTO `blog_post_category` (`id`,`post_id`,`category_name`,`category_id`) VALUES ';
					foreach($category as $key=>$value)
						if(strlen(ltrim(rtrim($key)))>0)
							$queryString.="(NULL,'$postId','$value','$key'),";
					$queryString[strlen($queryString)-1]='';
					if(!$this->post->queryExec($queryString))
						$this->view->catMessage=$this->message(POST_CATEGORY2_UNSUCCESSFUL,'yellow');
				}				
			}
			else
				$this->view->message=$this->message(POST_ADD_UNSUCCESSFUL,'red');
		}
	}
	
	function admin_addcategory()
	{
		$this->view->title=CATEGORY_TITLE;
		# add new category 
		if($this->post->validate())
		{
			if($this->post->insert('blog_category',$this->post->data))
			{
				$this->view->catMessage=POST_CATEGORY_SUCCESSFULY;
				$this->view->catInfo=$this->post->select('blog_category')->
				where('`name`=\''.$this->post->data['name'].'\'')->fetchOne();
			}
			else
				$this->view->catMessage=POST_CATEGORY_UNSUCCESSFULY;	
		}
	}
	
	function admin_edit()
	{
		$this->view->title=EDIT_TITLE;
		if($this->post->validate())
		{
			# var
			$category=$this->post->data['category'];
			unset($this->post->data['category']);	
			$tags=$this->post->data['tags'];
			$tags=array_unique(explode(',',$tags));
			unset($this->post->data['tags']);
			$postId=ID;
			# update post 
			if($this->post->update('blog_post',$this->post->data,ID))
			{
				$this->view->message=$this->message(POST_EDIT_SUCCESSFULY);
				# update tags
				if(count($tags)>=1 && strlen($tags[0]))
				{
					if($this->post->delete('blog_tag',ID,'post_id')!==FALSE)
					{
						$queryString='INSERT INTO `blog_tag` (`id`,`post_id`,`name`) VALUES ';
						foreach($tags as $tag)
							if(strlen(ltrim(rtrim($tag)))>0)
								$queryString.="(NULL,'$postId','$tag'),";
						$queryString[strlen($queryString)-1]='';
						if(!$this->post->queryExec($queryString))
							$this->view->tagMessage=$this->message(POST_TAGS_UNSUCCESSFUL,'yellow');
					}
					else
						$this->view->tagMessage=$this->message(POST_TAGS_UNSUCCESSFUL,'red');
				}
				
				# update categorys
				if(gettype($category)!='NULL')
				{
					if($this->post->delete('blog_post_category',ID,'post_id')!==FALSE)
					{
						$queryString='INSERT INTO `blog_post_category` (`id`,`post_id`,`category_name`,`category_id`) VALUES ';
						foreach($category as $key=>$value)
							if(strlen(ltrim(rtrim($key)))>0)
								$queryString.="(NULL,'$postId','$value','$key'),";
						$queryString[strlen($queryString)-1]='';
						if(!$this->post->queryExec($queryString))
							$this->view->catMessage=$this->message(POST_CATEGORY2_UNSUCCESSFUL,'yellow');
					}
					else
						$this->view->catMessage=$this->message(POST_CATEGORY2_UNSUCCESSFUL,'yellow');
				}							
			}
			else
				$this->view->message=$this->message(POST_EDIT_UNSUCCESSFULY,'red');		
		}
		
		# view post and category
		if($_SESSION['role']=='admin')
			$queryString='SELECT p.id, p.post_title,p.post_short_content,p.post_full_content,p.post_status,p.post_comment_status,p.post_date,p.post_time, GROUP_CONCAT(DISTINCT c.category_id), GROUP_CONCAT(DISTINCT t.name) FROM blog_post AS p LEFT JOIN blog_post_category AS c ON c.post_id = p.id LEFT JOIN blog_tag as t ON t.post_id=p.id WHERE(p.id =\''.ID.'\')group by p.id';
		else
			$queryString='SELECT p.id, p.post_title,p.post_short_content,p.post_full_content,p.post_status,p.post_comment_status,p.post_date,p.post_time,p.post_author, GROUP_CONCAT(DISTINCT c.category_id), GROUP_CONCAT(DISTINCT t.name) FROM blog_post AS p LEFT JOIN blog_post_category AS c ON c.post_id = p.id LEFT JOIN blog_tag as t ON t.post_id=p.id WHERE(p.id =\''.ID.'\' AND p.post_author=\''.$_SESSION['username'].'\')group by p.id';		
		$this->view->post=$this->post->queryFetch($queryString)->fetchOne();
			
		# sake redady show and suppression repetitive data (*)
		$count=count($this->view->post['GROUP_CONCAT(DISTINCT c.category_id)']);
		$group_category=explode(',',$this->view->post['GROUP_CONCAT(DISTINCT c.category_id)']);
		
		# show information (*) in view layer
		$this->view->category_count=$count;
		$this->view->group_category=$group_category;
		
		
		# fetch catgory
		$this->view->category=$this->post->select('blog_category','id,name')->order()->fetchAll();
		
		# check post exist
		if(!$this->view->post)
			die('not access...');
			//$this->view->message=$this->message(POST_NOTFOUND,'yellow');		
	}
	
	function admin_delete()
	{
		 # delete post
		 if($_SESSION['role']=='admin')
		 	$query='DELETE FROM `blog_post` WHERE(`id`=\''.ID.'\')';
		 else	
			$query='DELETE FROM `blog_post` WHERE(`id`=\''.ID.'\' AND `post_author`=\''.$_SESSION['username'].'\')';
		if($this->post->queryExec($query))
		{
			$this->view->message=$this->message(POST_DELETE_SUCCESSFULY);
			# delete post comment
			$this->post->queryExec('DELETE FROM `blog_comment` WHERE(`post_id`=\''.ID.'\')');
		}
		else
			$this->view->message=$this->message(POST_DELETE_UNSUCCESSFUL,'red');
	}
	
	function view()
	{
		# add comment
		if($this->post->validate())
		{
			$this->post->data['post_id']=ID;
			if($this->post->data['site']=='http://www.')
				$this->post->data['site']=NULL;
				
			if($this->post->insert('blog_comment',$this->post->data))
				$this->view->message=COMMENT_ADD_SUCCESSFULY;
			else
				$this->view->message=COMMENT_ADD_UNSUCCESSFULY;	
		}
		# get data common in appController
		$this->appIndex();
		# view comment
		$this->view->comment=$this->post->select('blog_comment')->
		where('`message`=0 AND `hidden`=0 AND post_id=\''.ID.'\'')->order()->fetchAll();
		# view post
		$query='SELECT p.id, p.post_title,p.post_short_content,p.post_comment_status,p.post_date,p.post_time,p.post_extra,p.post_author,p.post_continue, GROUP_CONCAT(DISTINCT c.category_name) AS `category`, GROUP_CONCAT(DISTINCT t.name) AS `tag`, GROUP_CONCAT(DISTINCT t.id) AS `tag_id`,COUNT(`comment`.post_id) as `com` FROM blog_post AS p LEFT JOIN blog_post_category AS c ON c.post_id = p.id LEFT JOIN blog_tag as t ON t.post_id=p.id  LEFT JOIN blog_comment AS `comment` ON `comment`.post_id=p.id WHERE(`post_extra`=0 AND p.id=\''.ID.'\') group by p.id ORDER BY p.id'; 
		$this->view->post=$this->post->queryFetch($query)->fetchOne();	
	}
	
	function admin_list()
	{
		$this->view->title=LIST_TITLE;
		
		if($this->post->validate())
			$this->ensembleWork();
		
		# if user not search
		if(is_null($this->post->data['search']))
		{
			$queryString='SELECT p.id, p.post_title,p.post_short_content,p.post_full_content,p.post_status,p.post_comment_status,p.post_date,p.post_time,post_author, COUNT(c.id) FROM blog_post AS p LEFT JOIN blog_comment AS c ON c.post_id = p.id group by p.id';
			$this->view->post=$this->post->queryFetch($queryString)->order()->limit(ID*20,20)->fetchAll();
		}
		else
		{
			$queryString='SELECT p.id, p.post_title,p.post_short_content,p.post_full_content,p.post_status,p.post_comment_status,p.post_date,p.post_time,p.post_author, COUNT(c.id) FROM blog_post AS p LEFT JOIN blog_comment AS c ON c.post_id = p.id WHERE(`post_title` LIKE \'%'.$this->post->data['search'].'%\' or `post_short_content` LIKE \'%'.$this->post->data['search'].'%\' or `post_full_content` LIKE \'%'.$this->post->data['search'].'%\') group by p.id';
			$this->view->post=$this->post->queryFetch($queryString)->order()->fetchAll();
			$count=-1;
		}
		if(is_null($this->view->post))
			$this->view->message=$this->message(POST_LIST_SEARCH,'yellow');
		# create pages
		if($count!=-1)
		{
			$i=0;
			if($count['COUNT(id)']>=20)
				while($i*20<$count['COUNT(id)'])
					$page[$i]=$i++;	
			$this->view->page=$page;
		}
	}
	
	function ensembleWork()
	{
		if(!is_null($this->post->data['ids']))
		{
			switch($this->post->data['title'])
			{
				case 'publish':
					# publish post
					foreach($this->post->data['ids'] as $key=>$value)
						if($this->post->queryExec("UPDATE `blog_post` SET `post_status`='1' WHERE(`id`='$key')"))
							$this->view->message=$this->message(POST_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(POST_LIST_UNSUCCESSFUL,'red');	
				break;	
				case 'unpublish':
					# un publish post
					foreach($this->post->data['ids'] as $key=>$value)
						if($this->post->queryExec("UPDATE `blog_post` SET `post_status`='0' WHERE(`id`='$key')"))
							$this->view->message=$this->message(POST_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(POST_LIST_UNSUCCESSFUL,'red');	
				break;
				case 'delete':
					# delete post
					foreach($this->post->data['ids'] as $key=>$value)
						if($this->post->delete('blog_post',$key))
						{
							$this->view->message=$this->message(POST_LIST_SUCCESSFUL);
							$this->post->delete('blog_tag',$key,'post_id');
							$this->post->delete('blog_post_category',$key,'post_id');
						}
				break;
				case 'hiddencomment':
					# hide post comment
					foreach($this->post->data['ids'] as $key=>$value)
						if($this->post->queryExec("UPDATE `blog_comment` SET `hidden`='0' WHERE(`post_id`='$key')"))
							$this->view->message=$this->message(POST_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(POST_LIST_UNSUCCESSFUL,'red');
				break;	
				case 'showcomment':
					# show post comment
					foreach($this->post->data['ids'] as $key=>$value)
						if($this->post->queryExec("UPDATE `blog_comment` SET `hidden`='1' WHERE(`post_id`='$key')"))
							$this->view->message=$this->message(POST_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(POST_LIST_UNSUCCESSFUL,'red');
				break;
				case 'gracecomment':
					# grace post comment
					foreach($this->post->data['ids'] as $key=>$value)
						if($this->post->queryExec("UPDATE `blog_comment` SET `grace`='1' WHERE(`post_id`='$key')"))
							$this->view->message=$this->message(POST_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(POST_LIST_UNSUCCESSFUL,'red');
				break;	
				case 'ungracecomment':
					# un grace post comment
					foreach($this->post->data['ids'] as $key=>$value)
						if($this->post->queryExec("UPDATE `blog_comment` SET `grace`='0' WHERE(`post_id`='$key')"))
							$this->view->message=$this->message(POST_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(POST_LIST_UNSUCCESSFUL,'red');
				break;	
				case 'enablecomment':
					# enable post comment
					foreach($this->post->data['ids'] as $key=>$value)
						if($this->post->queryExec("UPDATE `blog_post` SET `post_comment_status`='1' WHERE(`id`='$key')"))
							$this->view->message=$this->message(POST_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(POST_LIST_UNSUCCESSFUL,'red');
				break;
				case 'disablecomment':
					# disable post comment
					foreach($this->post->data['ids'] as $key=>$value)
						if($this->post->queryExec("UPDATE `blog_post` SET `post_comment_status`='0' WHERE(`id`='$key')"))
							$this->view->message=$this->message(POST_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(POST_LIST_UNSUCCESSFUL,'red');
				break;		
				case 'deletecomment':
					# delete post comment
					foreach($this->post->data['ids'] as $key=>$value)
						if($this->post->delete('blog_post',$key))
						{
							$this->view->message=$this->message(POST_LIST_SUCCESSFUL);
							if(!$this->post->queryExec("UPDATE `blog_post` SET `post_comment_count`='0' WHERE(`id`='$key')"))
								$this->view->message=$this->message(POST_LIST_DELETE_UNSUCCESSFUL,'yellow');
						}
						else
							$this->view->message=$this->message(POST_LIST_UNSUCCESSFUL,'red');
				break;																																				
			}
		}
		else
			$this->view->message=$this->message(POST_LIST_SELECT,'yellow');		
	}
	
	function category()
	{
		# get data common in appController
		$this->appIndex();
		# get post
		$query='SELECT p.id, p.post_title,p.post_short_content,p.post_comment_status,p.post_date,p.post_time,p.post_extra,p.post_author,p.post_continue, GROUP_CONCAT(DISTINCT c.category_name) AS `category`,GROUP_CONCAT(DISTINCT c.category_id) AS `category_id`, GROUP_CONCAT(DISTINCT t.name) AS `tag`, GROUP_CONCAT(DISTINCT t.id) AS `tag_id`,COUNT(`comment`.post_id) as `com` FROM blog_post AS p LEFT JOIN blog_post_category AS c ON c.post_id = p.id LEFT JOIN blog_tag as t ON t.post_id=p.id LEFT JOIN blog_comment AS `comment` ON `comment`.post_id=p.id WHERE(`post_extra`=0 AND c.category_id=\''.RULE.'\') group by p.id ORDER BY p.id DESC LIMIT '.ID*PAGE.','.PAGE;
		$this->view->post=$this->post->queryFetch($query)->fetchAll();
		#create page
		$count=$this->post->select('blog_post','COUNT(id)')->fetchOne();
		$i=0;
		if((int)$count['COUNT(id)']>=PAGE)
		  while($i*PAGE<$count['COUNT(id)'])
			  $page[$i]=$i++;	
		$this->view->page=$page;				
	}
}
?>