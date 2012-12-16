<?PHP

namespace Raspina\Db\Driver\Mysqli;

use Raspina\Db\Driver;
use Raspina\Db\Exception;

class Connection extends Driver\ConnectionAbstract
{
    public function connect()
    {
        if($this->connected){
            return;
        }
        
        if($this->error){
            throw new Exception\Error('connection has been error');
        }
        
        $this->_resource = new \mysqli($this->_hostname, $this->_username, $this->_password, $this->_database);
		
        if($this->_resource->connect_errno){
            $this->error = true;
            throw new Exception\Error('connection con not connected');
        }
        
        $this->connected = true;
    }
    
    public function disconnect()
    {
        if($this->noconnect || $this->disconnect){
            return;
        }
        
        if($this->error){
            throw new Exception\Error('connection has been error');
        }
        
        if(!$this->_resource->close()){
            $this->error = true;
            throw new Exception\Error('connection con not disconnect');
        }
        
        $this->disconnect = true;
    }
    
    public function execute()
    {
        if(!$this->connected){
            $this->connect();
        }
        
        if($this->error){
            throw new Exception\Error('connection has been error');
        }
        
        if(!isset($this->_sql) || empty($this->_sql)){
            throw new Exception\Error('sql not isset or sql empty');
        }
        
        // reset result
        $this->_result = false;
        // execute
        $this->_result = $this->_resource->query($this->_sql);
        if($this->_resource->errno){
            $this->error = true;
            throw new Exception\Error('connection con not execute');
        }
        
        if($this->_result instanceof \Mysqli_Result){
            $this->_result = new Result($this->_result);
        }
    }
}