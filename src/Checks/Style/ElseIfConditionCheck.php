<?php

namespace HippoPHP\Hippo\Checks\Naming;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;

class ElseIfConditionCheck extends AbstractCheck implements CheckInterface
{
    /**
     * @return string
     */
    public function getConfigRoot()
    {
        return 'style.elseif';
    }

    /**
     * checkFileInternal(): defined by AbstractCheck.
     *
     * @see AbstractCheck::checkFileInternal()
     *
     * @param CheckContext $checkContext
     * @param Config       $config
     *
     * @return void
     */
    protected function checkFileInternal(CheckContext $checkContext, Config $config)
    {
        $file = $checkContext->getFile();
        $tokens = $checkContext->getTokenList();

        try {
            do {
                // Jump us to the next token we want to check.
                $tokens->seekToType(T_ELSE);

                // Move to the next token.
                $token = $tokens->next()->current();

                // Check that the next token is not equal to T_WHITESPACE T_IF.
                if ($token->isType(T_WHITESPACE)) {
                    $nextToken = $tokens->next()->current(); // Move forward.
                    if ($nextToken->isType(T_IF)) {
                        $this->addViolation(
                            $file,
                            $token->getLine(),
                            $token->getColumn(),
                            'Use `elseif` rather than `else if`'
                        );
                    }
                }
            } while ($tokens->valid());
        } catch (\HippoPHP\Tokenizer\Exception\OutOfBoundsException $e) {
            // Ignore the exception, we're at the end of the file.
        }
    }
}
