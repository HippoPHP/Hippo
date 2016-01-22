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

namespace HippoPHP\Hippo\Checks;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Config\Config;

/**
 * Check Interface.
 * Rules implementing this interface will be visited for every file.
 *
 * @author James Brooks <jbrooksuk@me.com>
 */
interface CheckInterface
{
    /**
     * Checks a file.
     *
     * @param \HippoPHP\Hippo\CheckContext  $checkContext
     * @param \HippoPHP\Hippo\Config\Config $config
     *
     * @return \HippoPHP\Hippo\CheckResult
     */
    public function checkFile(CheckContext $checkContext, Config $config);

    /**
     * Returns the configuration root.
     *
     * @return string
     */
    public function getConfigRoot();
}
