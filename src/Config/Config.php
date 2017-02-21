<?php

/*
 * This file is part of Hippo.
 *
 * (c) James Brooks <james@alt-three.com>
 * (c) Marcin Kurczewski <rr-@sakuya.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HippoPHP\Hippo\Config;

use HippoPHP\Hippo\Exception\BadConfigKeyException;

class Config
{
    /**
     * @var array<*,*>
     */
    private $array;

    /**
     * @param array<*,*> $array
     */
    public function __construct(array $array = [])
    {
        $this->array = $this->_normalizeArray($array);
    }

    /**
     * @param string $key
     * @param mixed  $defaultValue
     *
     * @throws BadConfigKeyException if default value wasn't supplied and there were problems retrieving the key
     *
     * @return mixed
     */
    public function get($key, $defaultValue = null)
    {
        if (func_num_args() === 1) {
            $current = &$this->navigateToKey($key, false);
        } else {
            try {
                $current = &$this->navigateToKey($key, false);
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
     * @param mixed  $value
     */
    public function set($key, $value)
    {
        $current = &$this->navigateToKey($key, true);
        $current = is_array($value)
            ? $this->_normalizeArray($value)
            : $value;
    }

    /**
     * @param string $key
     */
    public function remove($key)
    {
        try {
            $current = &$this->navigateToKey($key, false);
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
            $output[$this->normalizeKey($key)] = is_array($value)
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
    private function normalizeKey($key)
    {
        return trim(str_replace('_', '', strtolower($key)));
    }

    /**
     * @param string $key
     * @param bool   $createSections
     *
     * @throws BadConfigKeyException
     *
     * @return mixed reference to the branch under given key
     */
    private function &navigateToKey($key, $createSections)
    {
        $current = &$this->array;
        foreach (explode('.', $key) as $key) {
            if (!is_array($current)) {
                if ($createSections) {
                    $current = [];
                } else {
                    throw new BadConfigKeyException('Trying to access child of a scalar value: '.$key);
                }
            }

            if (!isset($current[$this->normalizeKey($key)])) {
                if ($createSections) {
                    $current[$this->normalizeKey($key)] = [];
                } else {
                    throw new BadConfigKeyException('Trying to access a node that doesn\'t exist: '.$key);
                }
            }

            $current = &$current[$this->normalizeKey($key)];
        }

        return $current;
    }
}
