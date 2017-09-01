<?php

namespace Config;

class Value
{

    public $name;
    public $value;

    /**
     * Create Value object
     * @param string $name
     * @param mixed $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $result = null;
        if ($this->name == $name)
        {
            $result = $this->value;
        }
        return $result;
    }

}
