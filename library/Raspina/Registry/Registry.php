<?php
# namespace raspina\registry;
final class registry
{
    protected $registry = NULL;

    public function __call($method, $arguments)
    {
        return call_user_func_array(array($this->registry, $method), $arguments);
    }

    # public function __construct($array = array(), $flags = parent::ARRAY_AS_PROPS)
	public function __construct($array = array())
    {
        # parent::__construct($array, $flags);
        # return;
    }

    public function __destruct()
    {
        unset($this->registry);
        return;
    }

    public function reset()
    {
        $this->registry = array();
    }

    public function add($name, $value)
    {
        $this->registry[$name] = $value;
        return;
    }

    public function addMultiple($input = array())
    {
        if(is_array($input) || is_object($input))
        {
            foreach($input as $key=>$value)
            {
                $this->add($key, $value);
            }
        }

        return;
    }

    public function addDefault($name, $defaultValue = NULL)
    {
        return $this->add($name, $this->get($name, $defaultValue));
    }

    public function remove($name)
    {
        unset($this->registry[$name]);
        return;
    }

    public function removeMultiple($input = NULL)
    {
        if(is_array($input) || is_object($input))
        {
            foreach($input as $value)
            {
                $this->remove($value);
            }
            return;
        }

        return FALSE;
    }

    public function exists($name)
    {
        return isset($this->registry[$name]);
    }

    public function existsInArray($object, $name)
    {
        if($this->exists($name) && @in_array($object, $this->registry[$name]))
        {
            return TRUE;
        }

        return FALSE;
    }

    public function get($name)
    {
        if(array_key_exists($name, $this->registry))
        {
            return $this->registry[$name];
        }

        return NULL;
    }

    public function getClass()
    {
        return get_class($this->registry);
    }

    public function toString()
    {
        return (string)get_class($this);
    }

    public function toArray()
    {
        return (array)$this->registry;
    }

    public function toObject()
    {
        return (object)$this->registry;
    }
} 

