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

namespace HippoPHP\Hippo\Exception;

class ShutdownException extends \Exception implements ExceptionInterface
{
    /**
     * @var int
     */
    private $exitCode;

    /**
     * @param int $exitCode
     */
    public function __construct($exitCode)
    {
        $this->exitCode = $exitCode;
    }

    /**
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }
}
