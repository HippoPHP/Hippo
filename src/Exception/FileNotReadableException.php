<?php

/*
 * This file is part of Hippo.
 *
 * (c) James Brooks <jbrooksuk@me.com>
 * (c) Marcin Kurczewski <rr-@sakuya.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HippoPHP\Hippo\Exception;

/**
 */
class FileNotReadableException extends \Exception implements ExceptionInterface
{
    /**
     * @param string $path
     */
    public function __construct($path)
    {
        parent::__construct('File not readable: '.$path);
    }
}
