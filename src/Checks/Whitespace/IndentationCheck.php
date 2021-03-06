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

namespace HippoPHP\Hippo\Checks\Whitespace;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\Violation;

class IndentationCheck extends AbstractCheck implements CheckInterface
{
    // TODO: add "auto", which checks only for consistency
    const INDENT_STYLE_SPACE = 'space';
    const INDENT_STYLE_TAB = 'tab';

    /**
     * Style of indent.
     * Either 'tab' or 'space'.
     *
     * @var string
     */
    protected $indentStyle = self::INDENT_STYLE_SPACE;

    /**
     * Number of indentation characters per-level.
     *
     * @var int
     */
    protected $indentCount = 4;

    /**
     * Sets the indentation style.
     *
     * @param string $style
     *
     * @return \HippoPHP\Hippo\Checks\Whitespace\IndentationCheck
     */
    public function setIndentStyle($style)
    {
        $style = strtolower($style);

        switch ($style) {
            case self::INDENT_STYLE_SPACE:
            case self::INDENT_STYLE_TAB:
                $this->indentStyle = $style;
                break;
        }

        return $this;
    }

    /**
     * Sets the indentation count.
     *
     * @param int $count
     *
     * @return \HippoPHP\Hippo\Checks\Whitespace\IndentationCheck
     */
    public function setIndentCount($count)
    {
        $this->indentCount = max(1, (int) $count);

        return $this;
    }

    /**
     * @return string
     */
    public function getConfigRoot()
    {
        return 'file.indentation';
    }

    /**
     * checkFileInternal(): defined by AbstractCheck.
     *
     * @see AbstractCheck::checkFileInternal()
     *
     * @param \HippoPHP\Hippo\CheckContext  $checkContext
     * @param \HippoPHP\Hippo\Config\Config $config
     */
    protected function checkFileInternal(CheckContext $checkContext, Config $config)
    {
        $file = $checkContext->getFile();

        $this->setIndentStyle($config->get('style', $this->indentStyle));
        $this->setIndentCount($config->get('count', $this->indentCount));

        $indentation = $this->getBaseIndentation();
        $lines = $this->getLines($checkContext->getTokenList());

        $level = 0;
        foreach ($lines as $lineNumber => $line) {
            $actualIndentation = '';
            if (count($line) > 0) {
                if ($line[0]->isType(T_WHITESPACE)) {
                    $actualIndentation = $line[0]->getContent();
                }
            }

            foreach ($line as $token) {
                $content = $token->getContent();
                if ($content === '}' || $content === ')' || $content === ']') {
                    $level--;
                }
            }

            $expectedIndentation = $level > 0 ? str_repeat($indentation, $level) : '';

            if ($expectedIndentation !== $actualIndentation) {
                $this->addViolation(
                    $file,
                    $lineNumber,
                    count($line) > 0 ? $line[0]->getColumn() + strlen($line[0]->getContent()) : 1,
                    sprintf('Unexpected indentation (expected: %s, actual: %s)',
                        $this->escape($expectedIndentation),
                        $this->escape($actualIndentation)),
                    Violation::SEVERITY_WARNING
                );
            }

            foreach ($line as $token) {
                $content = $token->getContent();
                if ($content === '{' || $content === '(' || $content === '[') {
                    $level++;
                }
            }
        }
    }

    private function getBaseIndentation()
    {
        $char = '';
        if ($this->indentStyle === self::INDENT_STYLE_SPACE) {
            $char = ' ';
        } elseif ($this->indentStyle === self::INDENT_STYLE_TAB) {
            $char = "\t";
        }

        return str_repeat($char, $this->indentCount);
    }

    private function getLines($tokenList)
    {
        $lines = [];
        $line = [];
        $lineNumber = 1;
        foreach ($tokenList as $token) {
            $line[] = $token;
            // TODO: Fix end of line token.
            if ($token->isType(TokenType::TOKEN_EOL)) {
                $lines[$lineNumber] = $line;
                $line = [];
                $lineNumber++;
            }
        }
        $lines[$lineNumber] = $line;

        return $lines;
    }

    private function escape($string)
    {
        return str_replace(["\t", ' '], ['\\t', '\\ '], $string);
    }
}
