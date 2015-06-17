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

namespace HippoPHP\Hippo\Checks\Naming;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\Violation;

class PrivateVariableNamingCheck extends AbstractCheck implements CheckInterface
{
    private $pattern = '/\$([a-z_\x7f-\xff]+)([a-zA-Z0-9\x7f-\xff]+)?/';

    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @return string
     */
    public function getConfigRoot()
    {
        return 'style.private_variable_naming';
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

        $this->setPattern($config->get('pattern', $this->pattern));

        try {
            do {
                // Jump us to the next token we want to check.
                $tokens->seekToType(T_PRIVATE)->skipToNextNonWhitespace();
                $token = $tokens->current();

                if ($token->isType(T_VARIABLE)) {
                    if (!preg_match($this->pattern, $token->getContent())) {
                        $this->addViolation(
                            $file,
                            $token->getLine(),
                            $token->getColumn(),
                            sprintf(
                                'Private variable `%s` should follow a `%s` pattern',
                                $token->getContent(),
                                addslashes($this->pattern)
                            ),
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
