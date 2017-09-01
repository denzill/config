<?php

namespace Config;

class Section
{

    private $name;
    private $debug = false;
    private $values = array();

    /**
     * Create Section object
     * @param type $name
     * @param type $data
     */
    public function __construct($name, $data = array())
    {
        $this->debug(__METHOD__ . " Started, name: " . $name);
        $this->name = $name;
        foreach ($data as $param => $value)
        {
            $this->$param = new Value($param, $value);
        }
        $this->debug(__METHOD__ . " End");
    }

    /**
     * Get value of Config\Value object
     * @param string $name Name of value
     * @return mixed|null value of Config\Value object or null if value not defined
     */
    public function __get($name)
    {
        $this->debug('Start ' . __METHOD__);
        $value = null;
        if (isset($this->values[$name]) && $this->values[$name] instanceof Value)
        {
            $value = $this->values[$name]->$name;
        }
        $this->debug('End ' . __METHOD__);
        return $value;
    }

    /**
     * Add Config\Value object into values array
     * @param string $name
     * @param Config\Value $value
     */
    public function __set($name, $value)
    {
        if (!($value instanceOf \Config\Value))
        {
            $value = new Value($name, $value);
        }
        $this->debug('Start ' . __METHOD__);
        $this->debug(__METHOD__ . ": value is a " . get_class($value));
        if (get_class($value) == 'Config\Value')
        {
            $this->debug(__METHOD__ . ": value is " . print_r($value->$name, 1));
        } else
        {
            $this->debug(__METHOD__ . ": value not \Value object");
        }
        $this->values[$name] = $value;
        $this->debug('End ' . __METHOD__);
    }

    /**
     * 
     * @return int
     */
    public function countValues()
    {
        return count($this->values);
    }

    /**
     * 
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * 
     */
    public function enableDebug()
    {
        echo (__METHOD__ . ": Enabling debug\n");
        $this->debug = true;
    }

    /**
     * 
     * @param string $data
     */
    public function debug($data)
    {
        if ($this->debug)
        {
            echo ($data . "\n");
        }
    }

}
