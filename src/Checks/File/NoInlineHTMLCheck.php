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

namespace HippoPHP\Hippo\Checks\Naming;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\Violation;
use PhpParser\Error as PhpParserError;
use PhpParser\Node\Stmt\InlineHTML;

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
     * @param Config       $config
     */
    protected function checkFileInternal(CheckContext $checkContext, Config $config)
    {
        $file = $checkContext->getFile();

        try {
            $ast = $checkContext->getSyntaxTree();

            foreach ($ast as $node) {
                if ($node instanceof InlineHTML) {
                    $this->addViolation(
                        $file,
                        $node->getLine(),
                        0,
                        'PHP files should not contain inline HTML.',
                        Violation::SEVERITY_WARNING
                    );
                }
            }
        } catch (PhpParserError $e) {
            // Ignore it, this check isn't for checking syntax errors.
        }
    }
}
