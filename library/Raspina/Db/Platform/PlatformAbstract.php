<?PHP

namespace Raspina\Db\Platform;

abstract class PlatformAbstract
{
    protected $_sql;
    
    abstract public function create($options);
    abstract public function read($options);
    abstract public function edit($options);
    abstract public function delete($options);
    
    public function __construct()
    {
        $this->_sql = '';
    }
    
    public function __toString()
    {
        return $this->_sql;
    }
    
    public function set($sql)
    {
        $this->_sql = $sql;
    }
    
    public function get()
    {
        return $this->_sql;
    }
}