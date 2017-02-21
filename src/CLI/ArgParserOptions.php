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
        $this->mark($argName, self::TYPE_FLAG);
    }

    public function markArray($argName)
    {
        $this->mark($argName, self::TYPE_ARRAY);
    }

    public function isFlag($argName)
    {
        return $this->isMarked($argName, self::TYPE_FLAG);
    }

    public function isArray($argName)
    {
        return $this->isMarked($argName, self::TYPE_ARRAY);
    }

    private function mark($argName, $type)
    {
        $this->marked[$type][] = $argName;
    }

    private function isMarked($argName, $type)
    {
        return isset($this->marked[$type]) && in_array($argName, $this->marked[$type]);
    }
}
