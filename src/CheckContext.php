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

namespace HippoPHP\Hippo;

use HippoPHP\Tokenizer\Tokenizer;
use PhpParser\Lexer\Emulative;
use PhpParser\Parser;

class CheckContext
{
    const CONTEXT_TOKEN_LIST = 'tokenList';
    const CONTEXT_AST = 'ast';

    /**
     * @var \HippoPHP\Hippo\LazyFactory
     */
    private $lazyFactory;

    /**
     * @var \HippoPHP\Hippo\File
     */
    private $file;

    /**
     * @param \HippoPHP\Hippo\File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
        $this->lazyFactory = new LazyFactory();
    }

    /**
     * @return \HippoPHP\Tokenizer\TokenListIterator
     */
    public function getTokenList()
    {
        $tokenListIterator = $this->lazyFactory->get(self::CONTEXT_TOKEN_LIST, function () {
            $tokenizer = new Tokenizer();

            return $tokenizer->tokenize($this->file->getSource());
        });
        $tokenListIterator->rewind();

        return $tokenListIterator;
    }

    /**
     * @return mixed
     */
    public function getSyntaxTree()
    {
        return $this->lazyFactory->get(self::CONTEXT_AST, function () {
            $parser = new Parser(new Emulative());
            $stmts = $parser->parse($this->file->getSource());

            return $stmts;
        });
    }

    /**
     * @return \HippoPHP\Hippo\File
     */
    public function getFile()
    {
        return $this->file;
    }
}
