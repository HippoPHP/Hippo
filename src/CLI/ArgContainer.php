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

namespace HippoPHP\Hippo\CLI;

/**
 * A container for command-line-interface arguments.
 */
class ArgContainer
{
    /**
     * @var array
     */
    private $longOptions = [];

    /**
     * @var array
     */
    private $shortOptions = [];

    /**
     * @var array
     */
    private $strayArguments = [];

    /**
     * @param string $arg
     * @param mixed  $value
     */
    public function setShortOption($arg, $value)
    {
        $this->shortOptions[$arg] = $value;
    }

    /**
     * @param string $arg
     * @param mixed  $value
     */
    public function setLongOption($arg, $value)
    {
        $this->longOptions[$arg] = $value;
    }

    /**
     * @param mixed $value
     */
    public function addStrayArgument($value)
    {
        $this->strayArguments[] = $value;
    }

    /**
     * @param string $arg
     */
    public function getShortOption($arg)
    {
        return isset($this->shortOptions[$arg]) ? $this->shortOptions[$arg] : null;
    }

    /**
     * @param string $arg
     */
    public function getLongOption($arg)
    {
        return isset($this->longOptions[$arg]) ? $this->longOptions[$arg] : null;
    }

    /**
     * @return mixed[]
     */
    public function getStrayArguments()
    {
        return $this->strayArguments;
    }

    /**
     * @return array
     */
    public function getLongOptions()
    {
        return $this->longOptions;
    }

    /**
     * @return array
     */
    public function getShortOptions()
    {
        return $this->shortOptions;
    }

    /**
     * @return array
     */
    public function getAllOptions()
    {
        return array_merge($this->longOptions, $this->shortOptions);
    }
}
