<?php

namespace Config;

/**
 * 
 */
class Config
{

    /**
     * File name
     * @var string 
     */
    private $file;
    private $sections = array();
    private $use_default;
    private $process_sections;
    private $allowed_sections = NULL;
    private $debug = false;

    /**
     * Create Config object
     * @param string $file
     * @param bool $use_default
     * @param bool|array $process_sections FALSE - don`t process sections, 
     *        TRUE or array with list allowed sections - process sections
     * @throws ConfigException
     */
    public function __construct($file = null, $use_default = false, $process_sections = true)
    {
        $this->file = $file;
        $this->use_default = $use_default;
        if ($process_sections === FALSE)
        {
            $this->process_sections = false;
        } else
        {
            $this->process_sections = true;
            if (is_array($process_sections))
            {
                $this->allowed_sections = array_values($process_sections);
            }
        }

        if (!is_null($this->file))
        {
            if ($use_default === true)
            {
                $this->loadDefaults();
            }
            if (!file_exists($file))
            {
                throw new ConfigException("File " . $file . " not exists");
            }
            $this->loadData($this->file);
        }
    }

    /**
     * Add Config\Section or Config/Value object to sections array
     * @param string $name
     * @param Section|mixed $value
     */
    public function __set($name, $value)
    {
        $this->debug('Start ' . __METHOD__);
        if (isset($this->sections[$name]) && $value instanceof Section)
        {
            foreach ($value->getValues() as $param => $values)
            {
                $this->sections[$name]->$param = $values;
            }
        } else
        {
            $this->sections[$name] = $value;
        }
        $this->debug('End ' . __METHOD__);
    }

    /**
     * Get Section object (if exist), empty Section or value
     * @param string $name Section or Value name 
     * @return \Config\Section|mixed
     */
    public function __get($name)
    {
        $this->debug('Start ' . __METHOD__);
        $result = null;
        if (isset($this->sections[$name]) && $this->sections[$name])
        {
            $result = $this->sections[$name];
            if ($result instanceof Value)
            {
                $result = $result->$name;
            }
        } else
        {
            $this->sections[$name] = new Section($name);
            $result = $this->sections[$name];
        }
        $this->debug('End ' . __METHOD__);
        return $result;
    }

    /**
     * Load data from ${filename}.ini.default
     */
    public function loadDefaults()
    {
        if (file_exists($this->file . '.default'))
        {
            $this->loadData($this->file . '.default');
        }
    }

    /**
     * Load data from $filename
     * @param string $filename
     */
    public function loadData($filename)
    {
        $data = parse_ini_file($filename, $this->process_sections, INI_SCANNER_TYPED);
        foreach ($data as $param => $value)
        {
            if (is_array($value))
            {
                $this->addSection($param, $value);
            } else
            {
                $this->addValue($param, $value);
            }
        }
    }

    /**
     * Add section to array
     * @param string $section
     * @param array $values
     */
    protected function addSection($section, $values)
    {
        if (is_array($values))
        {
            if (is_null($this->allowed_sections) || in_array($section, $this->allowed_sections))
            {
                $this->$section = new Section($section, $values);
            }
        } else
        {
            $this->$section = new Value($section, $values);
        }
    }

    /**
     * Add value to array
     * @param string $param
     * @param mixed $value
     */
    protected function addValue($param, $value)
    {
        $this->$param = new Value($param, $value);
    }

    /**
     * 
     * @return type
     */
    public function listSections()
    {
        return array_keys($this->sections);
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
