<?php

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
     * @param \HippoPHP\Hippo\CheckContext  $checkContext
     * @param \HippoPHP\Hippo\Config\Config $config
     *
     * @return \HippoPHP\Hippo\CheckResult
     */
    public function checkFile(CheckContext $checkContext, Config $config);

    /**
     * @return string
     */
    public function getConfigRoot();
}
