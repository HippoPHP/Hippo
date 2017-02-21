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

namespace HippoPHP\Hippo\Checks\Whitespace;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\Violation;

/**
 * This is the no trailing whitespace check class.
 *
 * @author James Brooks <james@alt-three.com>
 */
class NoTrailingWhitespaceCheck extends AbstractCheck implements CheckInterface
{
    /**
     * Returns the configuration root.
     *
     * @return string
     */
    public function getConfigRoot()
    {
        return 'whitespace.no_trailing_whitespace';
    }

    /**
     * checkFileInternal(): defined by AbstractCheck.
     *
     * @see AbstractCheck::checkFileInternal()
     *
     * @param \HippoPHP\Hippo\CheckContext $checkContext
     * @param \HippoPHP\Hippo\Config\Config       $config
     *
     * @return void
     */
    protected function checkFileInternal(CheckContext $checkContext, Config $config)
    {
        $file = $checkContext->getFile();
        $lines = $file->getLines();

        foreach ($lines as $lineNo => $line) {
            if (trim($line) === '') {
                continue;
            }

            $line = rtrim($line, "\r\n");
            if ($line !== rtrim($line)) {
                $this->addViolation(
                    $file,
                    $lineNo,
                    0,
                    'Excess trailing spaces at end of line.',
                    Violation::SEVERITY_INFO
                );
            }
        }
    }
}
