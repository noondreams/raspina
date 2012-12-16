<?PHP

namespace Raspina\Db\Platform\MySQL;

use Raspina\Db\Platform;
use Raspina\Db\Exception;

class MySQL extends Platform\PlatformAbstract
{
    public function create($options)
    {
        if(!isset($options['table'])){
            throw new Exception\InvalidArgument('in create query options, table not is set');
        }
        
        if(!isset($options['values']) || empty($options['values'])){
            throw new Exception\InvalidArgument('in create query options, names and values not is set');
        }
        
        if(!is_array($options['values'])){
            throw new Exception\InvalidArgument('in create query options, values musqt be array');
        }
        
        $this->_sql = 'INSERT INTO `'. $options['table'] 
            .'`(`'. str_replace('.', '`.`', implode('`,`', array_keys($options['values']))) 
            .'`) VALUES(\''. implode('\', \'', str_replace('\'', '\\\'', $options['values'])) .'\')';
    }
    
    public function read($options)
    {
        if(!isset($options['table'])){
            throw new Exception\InvalidArgument('in read query options, table not is set');
        }
        
        if((!isset($options['cols'])) || (strtolower($options['cols']) == 'all')){
            $options['cols'] = '*';
        }
        
        if($options['cols'] != '*'){
            if(is_string($options['cols'])){
                $options['cols'] = explode(',', str_replace(array('`', ' '), '', $options['cols']));
            }
            
            if(!is_array($options['cols'])){
                throw new Exception\InvalidArgument('in read query option, cols value invalid');
            }
            
            $options['cols'] = '`'. implode('`,`', str_reolace('.', '`.`', $opstions['cols'])) .'`';
        }
        
        $this->_sql = 'SELECT '. $options['cols'] .' FROM `'. $options['table'] .'`';
        if(isset($options['where'])){
            $this->_sql .= ' WHERE '. $this->_where((array)$options['where']);
        }
    }
    
    public function edit($options)
    {
        if(!isset($options['table'])){
            throw new Exception\InvalidArgument('in edit query options, table not is set');
        }
        
        if(!isset($options['update']) || empty($options['update'])){
            throw new Exception\InvalidArgument('in edit query options, update not is set');
        }
        
        if(!is_array($options['update'])){
            throw new Exception\InvalidArgument('in edit query options, update invalid');
        }
        
        $update = '';
        foreach($options['update'] as $field=>$value){
            $update .= "`{$field}`='{$value}', ";
        }
        $update = rtrim($update, ', ');
        
        $this->_sql = "UPDATE `{$options['table']}` SET {$update}";
        if(isset($options['where'])){
            $this->_sql .= ' WHERE '. $this->_where((array)$options['where']);
        }
    }
    
    public function delete($options)
    {
        if(!isset($options['table'])){
            throw new Exception\InvalidArgument('in delete query options, table not is set');
        }
        
        $this->_sql = "DELETE FROM `{$options['table']}`";
        if(isset($options['where'])){
            $this->_sql .= ' WHERE '. $this->_where((array)$options['where']);
        }
    }
    
    private function _where($where)
    {
        if(!is_array($where)){
            throw new Exception\InvalidArgument('where must be array');
        }
        
        if(!isset($where[0])){
            throw new Exception\InvalidArgument('where invalid');
        }
        
        if(!isset($where[1])){
            throw new Exception\InvalidArgument('where invalid');
        }
        
        if(!isset($where[2])){
            $where[2] = '=';
        }
        
        if(is_array($where[0])){
            $where[0] = $this->_where($where[0]);
        }
        
        if(is_array($where[1])){
            $where[1] = $this->_where($where[1]);
        }
        
        return "`{$where[0]}` {$where[2]} '{$where[1]}'";
    }
}