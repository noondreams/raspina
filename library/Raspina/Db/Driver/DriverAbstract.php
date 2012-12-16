<?PHP

namespace Raspina\Db\Driver;

use Raspina\Db\Exception;

abstract class DriverAbstract
{
    protected $_connection;
    
    abstract public function setConnection($connection);
    abstract public function getPlatform();
    
    public function __construct($connection)
    {
        $this->setConnection($connection);
        if(!$this->_connection instanceof ConnectionAbstract){
            throw new Exception\Error('connection invalid');
        }
    }
    
    public function __get($name)
    {
        switch($name){
            case 'connection':
                return $this->_connection;
            case 'platform':
                return $this->getPlatform();
            default:
                break;
        }
        
        throw new Exception\Error("'{$name}' property not found");
    }
}