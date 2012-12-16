<?php
class database extends PDO
{
	private $queryString;
	private $result;
	function __construct()
	{
		echo 'connect!';
		parent::__construct('mysql:host='.DB_HOST.';dbname='.DB_NAME ,DB_USERNAME, DB_PASSWORD);
		$this->exec('SET NAMES utf8');
	}
	
	function insert($table=ACTION,$data)
	{
		$field='id';
		$value="'NULL'";
		foreach($data as $f=>$v)
		{
			$field.=" ,".$f;
			$value.=" ,'".$v."'";
		}
		return $this->exec("INSERT INTO $table ($field) VALUES ($value)");
	}
	
	function update($table=ACTION,$id=ID,$data)
	{	
		foreach($data as $f=>$v)
			$field[]=$f.'=:'.$f;
		$field=implode(',',$field);
		
		$queryString="UPDATE `$table` SET `$field`";
		$this->where("`id`=$id");
		
		$result=$this->prepare($queryString);
		foreach($data as $f=>$v)	
			$result->bindValue(':'.$f,$v);
		$result->execute();
		return $result->execute();
	}
	
	function delete($table=ACTION,$id=ID,$field='id')
	{
		return $this->exec("DELETE FROM `$table` WHERE(`$field`='$id')");
	}
	
	function select($table=ACTION,$col='*')
	{
		if(is_array($col))
			$col=implode(',',$col);
		$this->queryString="SELECT $col FROM $table";
		return $this;
	}
	
	function queryExec($queryString)
	{
		return $this->exec($queryString);		
	}
	
	function queryFetch($queryString)
	{
		$this->queryString=$queryString;
		return $this;	
	}
	
	function fetchOne()
	{
		$result=$this->query($this->queryString);
		return $result->fetch(PDO::FETCH_ASSOC);
	}
	
	function fetchAll()
	{
		$result=$this->query($this->queryString);
		return $result->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function where($whereString)
	{
		$this->queryString.=" WHERE($whereString)";
		return $this;
	}
	
	function limit($start,$result)
	{
		$this->queryString.=" LIMIT $start , $result";
		return $this;
	}
	
	function order($columnName='id',$orderBy='DESC')
	{
		$this->queryString.=" ORDER BY $columnName $orderBy";
		return $this;
	}
}
?>