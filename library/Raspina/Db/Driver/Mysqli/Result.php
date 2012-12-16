<?PHP

namespace Raspina\Db\Driver\Mysqli;

use Raspina\Db\Driver;
use Raspina\Db\Exception;

class Result extends Driver\ResultAbstract
{
    public function setResource($resource)
    {
        if(!$resource instanceof \Mysqli_Result){
            throw new Exception\InvalidArgument('resource invalid');
        }
        
        $this->_resource = $resource;
    }
    
    public function fetch()
    {
        return $this->_resource->fetch_assoc();
    }
}