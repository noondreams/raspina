<?PHP
namespace Raspina\Db\Driver\Mysqli;

use Raspina\Db\Driver;
use Raspina\Db\Exception;

class Mysqli extends Driver\DriverAbstract
{
    public function setConnection($connection)
    {
        if(is_array($connection)){
            $connection = new Connection($connection);
        }
        
        if(!$connection instanceof Connection){
            throw new Exception\InvalidArgument('connaction invalid');
        }
        
        $this->_connection = $connection;
    }
    
    public function getPlatform()
    {
        return 'MySQL';
    }
}