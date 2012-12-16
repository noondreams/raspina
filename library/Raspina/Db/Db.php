<?PHP

namespace Raspina\Db;

class Db
{
    private $_driver;
    private $_platform;
    
    public function __construct($connection)
    {
        $this->setDriver($connection);
        $this->setPlatform($this->_driver->platform);
    }
	
    public function __get($name)
    {
        switch($name){
            case 'query':
                return $this->_platform;
            default:
                break;
        }
        
        throw new Exception\Error("'{$name}' property not found");
    }
    
    public function execute($parameters=null)
    {
        if(isset($parameters)){
            $sql = $this->_platform->get();
            foreach((array)$parameters as $name=>$value){
                $sql =str_replace(":{$name}", $value, $sql);
            }
            
            $this->_platform->set($sql);
        }
        
        $this->_driver->connection->setSql($this->_platform->get());
        $this->_driver->connection->execute();
    }
    
    public function fetch()
    {
        return $this->_driver->connection->result->fetch();
    }
    
    public function fetchAll()
    {
        return $this->_driver->connection->result->fetchAll();
    }
    
    public function create($options)
    {
        $this->_platform->create($options);
        $this->execute();
    }
    
    public function read($options)
    {
        $this->_platform->read($options);
        $this->execute();
        return $this->fetchAll();
    }
    
    public function edit($options)
    {
        $this->_platform->edit($options);
        $this->execute();
    }
    
    public function delete($option)
    {
		echo 'ehsan rezaee';
        $this->_platform->delete($options);
        $this->execute();
    }
    
    public function setDriver($connection)
    {
        if(!isset($connection['driver'])){
            throw new Exception\InvalidArgument('connection driver not is set');
        }
        
        $driver = $connection['driver'];
        unset($connection['driver']);
        switch(strtolower($driver)){
            case 'mysqli':
                $this->_driver = new Driver\Mysqli\Mysqli($connection);
                break;
            default:
                throw new Exception\InvaliArgument("'{$driver}' driver not found");
                break;
        }
        
        if(!$this->_driver instanceof Driver\DriverAbstract){
            throw new Exception\Error('driver invalid');
        }
    }
    
    public function setPlatform($platformName)
    {
        switch(strtolower($platformName)){
            case 'mysql':
                $this->_platform = new Platform\MySQL\MySQL();
                break;
            default:
                throw new Exception\InvalidArgument("'{$platformName}' platform not found");
                break;
        }
        
        if(!$this->_platform instanceof Platform\PlatformAbstract){
            throw new Exception\Error('platform invalid');
        }
    }
}