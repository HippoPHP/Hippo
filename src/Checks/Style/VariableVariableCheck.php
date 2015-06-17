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

namespace HippoPHP\Hippo\Checks\Style;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\Violation;

/**
 * TODO: Rewrite this check using the AbstractSyntaxTree.
 * It's very hacky right now.
 */
class VariableVariableCheck extends AbstractCheck implements CheckInterface
{
    /**
     * @return string
     */
    public function getConfigRoot()
    {
        return 'style.var_var';
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
        $tokens = $checkContext->getTokenList();

        try {
            do {
                // Jump us to the next token we want to check.
                $tokens->seekToType(T_VARIABLE);
                $token = $tokens->current();

                // If the content !== $ then go back a token, is that $?
                if ($token->getContent() !== '$') {
                    $prevTokenList = clone $tokens;
                    $prevToken = $prevTokenList->prev()->current();
                    if ($prevToken->getContent() === '$') {
                        $this->addViolation(
                            $file,
                            $token->getLine(),
                            $token->getColumn(),
                            'Do not use variable variables.',
                            Violation::SEVERITY_ERROR
                        );
                    }
                }
            } while ($tokens->valid());
        } catch (\HippoPHP\Tokenizer\Exception\OutOfBoundsException $e) {
            // Ignore the exception, we're at the end of the file.
        }
    }
}
