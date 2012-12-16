<?PHP

namespace Raspina\Db\Driver;

use Raspina\Db\Exception;

abstract class ConnectionAbstract
{
    const STATUS_NOCONNECT  = 0;
    const STATUS_CONNECTED  = 1;
    const STATUS_DISCONNECT = 2;
    const STATUS_ERROR      = 3;
    
    protected $_hostname;
    protected $_username;
    protected $_password;
    protected $_database;
    protected $_resource;
    protected $_sql;
    protected $_result;
    protected $_status;
    
    abstract public function connect();
    abstract public function disconnect();
    abstract public function execute();
    
    public function __construct($connection)
    {
        if(!is_array($connection)){
            throw new Exception\InvalidArgument('connection info must be array');
        }
        
        if(!isset($connection['hostname'])){
            throw new Exception\InvalidArgument('connection hostname not is set');
        }
        
        $this->_hostname = $connection['hostname'];
        
        if(!isset($connection['username'])){
            throw new Exception\InvalidArgument('connection username not is set');
        }
        
        $this->_username = $connection['username'];
        
        if(!isset($connection['password'])){
            throw new Exception\InvalidArgument('connection password not is set');
        }
        
        $this->_password = $connection['password'];
        
        if(!isset($connection['database'])){
            throw new Exception\InvalidArgument('connection database not is set');
        }
        
        $this->_database = $connection['database'];
        $this->_result = false;
        $this->_sql = '';
        $this->_status = self::STATUS_NOCONNECT;
    }
    
    public function __destruct()
    {
        $this->disconnect();
    }
    
    public function __set($name, $value)
    {
        switch($name){
            case 'noconnect':
                $this->_status = self::STATUS_NOCONNECT;
                return;
            case 'connected':
                $this->_status = self::STATUS_CONNECTED;
                return;
            case 'disconnect':
                $this->_status = self::STATUS_DISCONNECT; 
                return;
            case 'error':
                $this->_status = self::STATUS_ERROR;
                return;
            default:
                break;
        }
        
        throw new Exception\Error("'{$name}' property not found");
    }
    
    public function __get($name)
    {
        switch($name){
            case 'noconnect':
                return ($this->_status == self::STATUS_NOCONNECT);
            case 'connected':
                return ($this->_status == self::STATUS_CONNECTED);
            case 'disconnect':
                return ($this->_status == self::STATUS_DISCONNECT);
            case 'error':
                return ($this->_status == self::STATUS_ERROR);
            case 'resource':
                return $this->_resource;
            case 'result':
                return $this->_result;
            case 'sql':
                return $this->_sql;
            default:
                break;
        }
        
        throw new Exception\Error("'{$name}' property not found");
    }
    
    public function setSql($sql)
    {
        $this->_sql = (string)$sql;
    }
}