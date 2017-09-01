<?php

namespace Config;

class Testing
{

    private $cnf;

    public function __construct()
    {
        $this->cnf = new Config('test.ini', true, array('filled_section', 'section2'));
    }

    /**
     * 
     * @assert ('test.ini',false) instanceOf 'Config\Config'
     * @assert ('test.ini',true) instanceOf 'Config\Config'
     * @assert ('test.ini',true,array('filled_section','section2')) instanceOf 'Config\Config'
     * @assert ('notexistent.ini') throws Config\ConfigException 
     */
    public function testCreate($file, $def = false, $sects = null)
    {
        return new Config($file, $def, $sects);
    }

    /**
     * 
     * @param string $section
     * @assert ('filled_section') == 3
     * @assert ('section2') == 3
     * @assert ('loose_section') == 0
     * @assert ('nonexistent') == 0
     */
    public function getSection($section)
    {
        return $this->cnf->$section->countValues();
    }

    /**
     * 
     * @param type $section
     * @param type $param
     * @return type
     * @assert ('filled_section','int_val') == 5
     * @assert ('filled_section','array1') == array('ddd',42)
     * @assert ('loose_section','loose_str') == null
     */
    public function getValue($section, $param)
    {

        return $this->cnf->$section->$param;
    }

    /**
     * 
     * @param string $section
     * @param string $valueName
     * @param mixed $value
     * @return mixed
     * @assert ('filled_section', 'int_val',10) == 10
     * @assert ('section2', 'new_str','new_str') == 'new_str'
     * @assert ('new_section', 'new_str2','new_str2') == 'new_str2'
     */
    public function setValue($section, $valueName, $value)
    {
        if ($section == 'new_section')
        {
            $this->cnf->enableDebug();
        }

        $this->cnf->$section->$valueName = $value;
        print_r($this->cnf->listSections());
        return $this->cnf->$section->$valueName;
    }

}
