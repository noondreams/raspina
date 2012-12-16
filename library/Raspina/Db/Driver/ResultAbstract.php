<?PHP

namespace Raspina\Db\Driver;

abstract class ResultAbstract
{
    protected $_resource;
    
    abstract public function setResource($resource);
    abstract public function fetch();
    
    public function __construct($resource)
    {
        $this->setResource($resource);
    }
    
    public function fetchAll()
    {
        $rows = false;
        while($row = $this->fetch()){
            $rows[] = $row;
        }
        
        return $rows;
    }
}