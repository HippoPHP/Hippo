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

namespace HippoPHP\Hippo\Checks\Syntax;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\Violation;
use PhpParser\Error as PhpParserError;
use PhpParser\Lexer\Emulative;
use PhpParser\Parser;

class SyntaxCheck extends AbstractCheck implements CheckInterface
{
    /**
     * @return string
     */
    public function getConfigRoot()
    {
        return 'syntax.check';
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

        $parser = new Parser(new Emulative());

        try {
            $parser->parse($file->getSource());
        } catch (PhpParserError $e) {
            $this->addViolation(
                $file,
                $e->getStartLine(),
                0,
                $e->getRawMessage(),
                Violation::SEVERITY_ERROR
            );
        }
    }
}
