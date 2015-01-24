<?php

namespace HippoPHP\Hippo\Config;

use HippoPHP\Hippo\Exception\BadConfigKeyException;

class Config
{
    /**
     * @var array<*,*>
     */
    private $_array;

    /**
     * @param array<*,*> $array
     */
    public function __construct(array $array = [])
    {
        $this->_array = $this->_normalizeArray($array);
    }

    /**
     * @param string $key
     * @param mixed $defaultValue
     *
     * @throws BadConfigKeyException if default value wasn't supplied and there were problems retrieving the key
     *
     * @return mixed
     */
    public function get($key, $defaultValue = null)
    {
        if (func_num_args() === 1) {
            $current = &$this->_navigateToKey($key, false);
        } else {
            try {
                $current = &$this->_navigateToKey($key, false);
            } catch (BadConfigKeyException $e) {
                return $defaultValue;
            }
        }

        return is_array($current)
            ? new self($current)
            : $current;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set($key, $value)
    {
        $current = &$this->_navigateToKey($key, true);
        $current = is_array($value)
            ? $this->_normalizeArray($value)
            : $value;
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function remove($key)
    {
        try {
            $current = &$this->_navigateToKey($key, false);
            $current = null;
        } catch (BadConfigKeyException $e) {
            // If we try to remove an empty node, don't error.
        }
    }

    /**
     * @param array<*,*> $array
     *
     * @return array<*,*>
     */
    private function _normalizeArray(array $array)
    {
        $output = [];
        foreach ($array as $key => $value) {
            $output[$this->_normalizeKey($key)] = is_array($value)
                ? $this->_normalizeArray($value)
                : $value;
        }

        return $output;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function _normalizeKey($key)
    {
        return trim(str_replace('_', '', strtolower($key)));
    }

    /**
     * @param string $key
     * @param boolean $createSections
     *
     * @throws BadConfigKeyException
     *
     * @return mixed reference to the branch under given key
     */
    private function &_navigateToKey($key, $createSections)
    {
        $current = &$this->_array;
        foreach (explode('.', $key) as $key) {
            if (!is_array($current)) {
                if ($createSections) {
                    $current = [];
                } else {
                    throw new BadConfigKeyException('Trying to access child of a scalar value: '.$key);
                }
            }

            if (!isset($current[$this->_normalizeKey($key)])) {
                if ($createSections) {
                    $current[$this->_normalizeKey($key)] = [];
                } else {
                    throw new BadConfigKeyException('Trying to access a node that doesn\'t exist: '.$key);
                }
            }

            $current = &$current[$this->_normalizeKey($key)];
        }

        return $current;
    }
}
