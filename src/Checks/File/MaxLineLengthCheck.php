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

namespace HippoPHP\Hippo\Checks\Line;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\Violation;

/**
 * Warns about the line length limits.
 */
class MaxLineLengthCheck extends AbstractCheck implements CheckInterface
{
    /**
     * Limits for emitting violations.
     *
     * @var int[]
     */
    protected $limits = [
        Violation::SEVERITY_ERROR   => 80,
        Violation::SEVERITY_WARNING => null,
        Violation::SEVERITY_INFO    => null,
    ];

    /**
     * Defines how many spaces a tab takes up.
     *
     * @var int
     */
    protected $tabExpand = 4;

    /**
     * Sets the error line length limit.
     *
     * @param int $violationLevel
     * @param int $length
     *
     * @return MaxLength
     */
    public function setLimit($violationSeverity, $length)
    {
        $length = ((int) $length);
        $this->limits[$violationSeverity] = $length > 0 ? $length : null;

        return $this;
    }

    /**
     * Sets how many spaces make up a tab.
     *
     * @param int $size
     *
     * @return MaxLength
     */
    public function setTabExpand($size)
    {
        $this->tabExpand = (int) $size;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfigRoot()
    {
        return 'file.max_line_length';
    }

    /**
     * checkFileInternal(): defined by AbstractCheck.
     *
     * @see AbstractCheck::checkFileInternal()
     *
     * @param CheckContext $checkContext
     * @param Config       $config
     */
    protected function checkFileInternal(CheckContext $checkContext, Config $config)
    {
        $file = $checkContext->getFile();
        $lines = $file->getLines();

        $severityError = Violation::SEVERITY_ERROR;
        $severityWarning = Violation::SEVERITY_WARNING;
        $severityInfo = Violation::SEVERITY_INFO;

        if (count($lines) > 0) {
            $this->setLimit($severityError, $config->get('error_limit', $this->limits[$severityError]));
            $this->setLimit($severityWarning, $config->get('warning_limit', $this->limits[$severityWarning]));
            $this->setLimit($severityInfo, $config->get('info_limit', $this->limits[$severityInfo]));
            $this->setTabExpand($config->get('tab_expand', $this->tabExpand));

            foreach ($lines as $line => $data) {
                $lineLength = iconv_strlen(
                    str_replace("\t", str_repeat(' ', $this->tabExpand), rtrim($data, "\r\n")),
                    $file->getEncoding()
                );

                $severity = null;

                foreach (Violation::getSeverities() as $severity) {
                    if (!isset($this->limits[$severity]) || $this->limits[$severity] === null) {
                        continue;
                    }

                    if ($lineLength <= $this->limits[$severity]) {
                        continue;
                    }

                    $this->addViolation(
                        $file,
                        $line,
                        0,
                        sprintf('Line is too long. [%d/%d]', $lineLength, $this->limits[$severity]),
                        $severity
                    );
                }
            }
        }
    }
}
