<?php

namespace HippoPHP\Hippo\Checks\Naming;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\Violation;

class NoInlineHTMLCheck extends AbstractCheck implements CheckInterface
{
        /**
         * @return string
         */
        public function getConfigRoot()
        {
            return 'file.no_inline_html';
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
            $ast = $checkContext->getSyntaxTree();

            foreach ($ast as $node) {
                if ($node instanceof \PhpParser\Node\Stmt\InlineHTML) {
                    $this->addViolation(
                        $file,
                        $node->getLine(),
                        0,
                        'PHP files should not contain inline HTML.',
                        Violation::SEVERITY_WARNING
                    );
                }
            }
        }
}
