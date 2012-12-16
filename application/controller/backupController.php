<?php
include_once('includes/message/backupMessage.php');
class backupController extends appController
{
	function __construct()
	{
		parent::__construct();
		$this->view->view();
	}
	
	function admin_add()
	{
		# get all tables
		$tables=array();
		$result=$this->backup->queryExec('SHOW TABLES');
		while($row = $this->backup->fetchRow($result))
			$tables[]=$row[0];
		# cycle through
		
		foreach($tables as $table)
		{
			$result=$this->backup->queryExec('SELECT * FROM `'.$table.'`');
			$num_fields = $this->backup->numFields($result);
			$return.='DROP TABLE IF EXISTS `'.$table.'`;';
			$row2 = $this->backup->fetchRow($this->backup->queryExec('SHOW CREATE TABLE '.$table));
			$return.= "\n\n".$row2[1].";\n\n";
			
			for ($i = 0; $i < $num_fields; $i++) 
			{
				while($row = $this->backup->fetchRow($result))
				{
					$return.= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<$num_fields; $j++) 
					{
						$row[$j] = addslashes($row[$j]);
						$row[$j] = ereg_replace("\n","\\n",$row[$j]);
						if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
						if ($j<($num_fields-1)) { $return.= ','; }
					}
					$return.= ");\n";
				}
			}
			$return.="\n\n\n";
		}	
		//save file
		require_once('includes/modules/pdate.php');
		$date=pdate('Y.m.d');
		$time=pdate('H.i.s');	
		$filename='backup['.$date .' '. $time.'].sql';
		$handle = fopen('includes/backup/'.$filename,'w');
		if(fwrite($handle,$return))
			$this->view->message=$this->message(BACKUP_SUCCESSFULY);
		else
			$this->view->message=$this->message(BACKUP_UNSUCCESSFULY,'red');	
		fclose($handle);		
	}
	
	function admin_list()
	{
		if($this->backup->validate())
			$this->ensembleWork();
		# set dir	
		$dir='includes/backup/';
		# open dir and read all files 
		if($open=opendir($dir))
		{
			while($file=readdir($open))
				$fileName[]=array('name'=>$file,'size'=>filesize('includes/backup/'.$file));
		}
		else
			$this->view->message=$this->message(DIRECTORY_NOTFOUND,'red');
		# sort files
		unset($fileName[0]);
		unset($fileName[1]);	
		if(!is_null($fileName))
			sort($fileName);
		# view files
		$this->view->files=$fileName;			
	}

	function ensembleWork()
	{
		if(!is_null($this->backup->data['ids']))
		{
			switch($this->backup->data['title'])
			{
				case 'delete':
					# delete file
					foreach($this->backup->data['ids'] as $key=>$value)
						if(unlink('includes/backup/'.$key.'].sql'))
							$this->view->message=$this->message(BACKUP_LIST_SUCCESSFUL);
						else
							$this->view->message=$this->message(BACKUP_LIST_UNSUCCESSFUL,'red');	
				break;																																
			}
		}
		else
			$this->view->message=$this->message(BACKUP_LIST_SELECT,'yellow');
	}
	
	function admin_restore()
	{
		# restore .sql
		if($this->mysqlImport($_POST['file']))
			$this->view->message=$this->message(BACKUP_EXEC_SUCCESSFULY);
		else
			$this->view->message=$this->message(BACKUP_EXEC_UNSUCCESSFULY,'red');	
	}
	
	function mysqlImport($filename) 
	{ 
		# Read the file 
		$lines = file('includes/backup/'.$filename); 
		if(!$lines) 
			return false; 
		$scriptfile = false; 
		# Get rid of the comments and form one jumbo line
		foreach($lines as $line) 
		{ 
			$line = trim($line); 
			if(!ereg('^--', $line)) 
				$scriptfile.=" ".$line; 
		} 
		if(!$scriptfile) 
			return false; 
		# Split the jumbo line into smaller lines
		$queries = explode(';', $scriptfile); 
		# Run each line as a query
		foreach($queries as $query) 
		{ 
			$query = trim($query); 
			if($query == "")
				continue;
			if(!$this->backup->queryExec($query)) 
				return false; 
		} 
		# All is well
		return true; 
	}	

	function admin_delete()
	{
		# delete file(.sql);
		if(unlink('includes/backup/'.$_POST['file']))
			$this->view->message=$this->message(BACKUP_DELETE_SUCCESSFULY);
		else
			$this->view->message=$this->message(BACKUP_DELETE_UNSUCCESSFULY,'red');	
	}	
}
?>