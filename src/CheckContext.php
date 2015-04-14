<?php

namespace HippoPHP\Hippo;

use HippoPHP\Hippo\File;
use HippoPHP\Hippo\LazyFactory;
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
     * @param File $file
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
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }
}
