<?php

namespace HippoPHP\Hippo\Checks\Naming;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;

class BitwiseCheck extends AbstractCheck implements CheckInterface
{
    /**
     * @var array
     */
    protected $tokens = [
        T_LOGICAL_AND,
        T_LOGICAL_OR,
    ];

    /**
     * @var array
     */
    protected $useLookup = [
        T_LOGICAL_AND => '&&',
        T_LOGICAL_OR  => '||',
    ];

    /**
     * @return string
     */
    public function getConfigRoot()
    {
        return 'style.bitwise';
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
                $token = $tokens->seekToType($this->tokens)->current();

                $using = $token->getContent();
                $should = $this->useLookup[$token->getType()];

                $this->addViolation(
                    $file,
                    trim($token->getLine()),
                    trim($token->getColumn()),
                    sprintf(
                        'Use bitwise condition %s instead of %s',
                        $using,
                        $should
                    )
                );
            } while ($tokens->valid());
        } catch (\HippoPHP\Tokenizer\Exception\OutOfBoundsException $e) {
            // Ignore the exception, we're at the end of the file.
        }
    }
}
