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

namespace HippoPHP\Hippo\Checks\Style;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\Violation;

class VariableNamingCheck extends AbstractCheck implements CheckInterface
{
    /**
     * Variable pattern.
     *
     * @var string
     */
    protected $pattern = '/\$([a-z_\x7f-\xff]+)([a-zA-Z0-9\x7f-\xff]+)?/';

    /**
     * Built in variables that could defy our pattern.
     *
     * @var string[]
     */
    protected $ignoredVariables = [
        '$GLOBALS',
        '$_SERVER',
        '$_GET',
        '$_POST',
        '$_FILES',
        '$_REQUEST',
        '$_SESSION',
        '$_ENV',
        '$_COOKIE',
        '$php_errormsg',
        '$HTTP_RAW_POST_DATA',
        '$http_response_header',
        '$argc',
        '$argv',
    ];

    /**
     * Sets the pattern to use for variable names.
     *
     * @param string $pattern
     *
     * @return void
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * {@inheritdoc}
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
     */
    protected function checkFileInternal(CheckContext $checkContext, Config $config)
    {
        $file = $checkContext->getFile();
        $tokens = $checkContext->getTokenList();

        $this->setPattern($config->get('pattern', $this->pattern));

        try {
            do {
                // Jump us to the next token we want to check.
                $tokens->seekToType(T_VARIABLE);
                $token = $tokens->current();

                if (in_array($token->getContent(), $this->ignoredVariables)) {
                    continue;
                }

                if (!preg_match($this->pattern, $token->getContent())) {
                    $this->addViolation(
                        $file,
                        $token->getLine(),
                        $token->getColumn(),
                        sprintf(
                            'Variable `%s` should follow the `%s` pattern',
                            $token->getContent(),
                            addslashes($this->pattern)
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
