<?php

namespace HippoPHP\Hippo\Checks\Style;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\Violation;

class VariableNamingCheck extends AbstractCheck implements CheckInterface
{
    private $_pattern = '/\$[a-z_][a-zA-Z0-9]+$/';

    public function setPattern($pattern)
    {
        $this->_pattern = $pattern;
    }

    /**
     * @return string
     */
    public function getConfigRoot()
    {
        return 'style.variable_naming';
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

        $this->setPattern($config->get('pattern', $this->_pattern));

        try {
            do {
                // Jump us to the next token we want to check.
                $tokens->seekToType(T_VARIABLE);
                $token = $tokens->current();

                if (! preg_match($this->_pattern, $token->getContent())) {
                    $this->addViolation(
                        $file,
                        $token->getLine(),
                        $token->getColumn(),
                        sprintf(
                            'Variable `%s` should follow the `%s` pattern',
                            $token->getContent(),
                            addslashes($this->_pattern)
                        ),
                        Violation::SEVERITY_ERROR
                    );
                }
            } while ($tokens->valid());
        } catch (\HippoPHP\Tokenizer\Exception\OutOfBoundsException $e) {
            // Ignore the exception, we're at the end of the file.
        }
    }
}
