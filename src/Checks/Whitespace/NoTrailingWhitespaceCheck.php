<?php

namespace HippoPHP\Hippo\Checks\Whitespace;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\Violation;

class NoTrailingWhitespaceCheck extends AbstractCheck implements CheckInterface
{
        /**
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
         * @param CheckContext $checkContext
         * @param Config $config
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
