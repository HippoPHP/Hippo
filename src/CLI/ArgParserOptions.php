<?php

namespace HippoPHP\Hippo\CLI;

class ArgParserOptions
{
    const TYPE_FLAG = 1;
    const TYPE_ARRAY = 2;

    /**
     * @var array
     */
    private $marked = [
        self::TYPE_FLAG  => [],
        self::TYPE_ARRAY => [],
    ];

    public function markFlag($argName)
    {
        $this->_mark($argName, self::TYPE_FLAG);
    }

    public function markArray($argName)
    {
        $this->_mark($argName, self::TYPE_ARRAY);
    }

    public function isFlag($argName)
    {
        return $this->_isMarked($argName, self::TYPE_FLAG);
    }

    public function isArray($argName)
    {
        return $this->_isMarked($argName, self::TYPE_ARRAY);
    }

    private function _mark($argName, $type)
    {
        $this->marked[$type][] = $argName;
    }

    private function _isMarked($argName, $type)
    {
        return isset($this->marked[$type]) && in_array($argName, $this->marked[$type]);
    }
}
