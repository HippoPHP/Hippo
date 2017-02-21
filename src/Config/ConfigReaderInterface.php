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

interface ConfigReaderInterface
{
    /**
     * @param string $filename
     *
     * @return Config
     */
    public function loadFromFile($filename);

    /**
     * @param string $string
     *
     * @return Config
     */
    public function loadFromString($string);
}
