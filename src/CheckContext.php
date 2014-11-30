<?php

	namespace HippoPHP\Hippo;

	use \HippoPHP\Hippo\Exception;
	use \HippoPHP\Hippo\File;
	use \HippoPHP\Hippo\LazyFactory;
	use \HippoPHP\Tokenizer\Tokenizer;
	use \PhpParser\Parser;
	use \PhpParser\Lexer\Emulative;

	class CheckContext {
		const CONTEXT_TOKEN_LIST = 'tokenList';
		const CONTEXT_AST = 'ast';

		/**
		 * @var LazyFactory
		 */
		private $_lazyFactory;

		/**
		 * @var File
		 */
		private $_file;

		/**
		 * @param File $file
		 */
		public function __construct(File $file) {
			$this->_file = $file;
			$this->_lazyFactory = new LazyFactory();
		}

		/**
		 * @return \HippoPHP\Tokenizer\TokenListIterator
		 */
		public function getTokenList() {
			$tokenListIterator = $this->_lazyFactory->retrieve(self::CONTEXT_TOKEN_LIST, function() {
				$tokenizer = new Tokenizer();
				return $tokenizer->tokenize($this->_file->getSource());
			});
			$tokenListIterator->rewind();
			return $tokenListIterator;
		}

		/**
		 * @return mixed
		 */
		public function getSyntaxTree() {
			return $this->_lazyFactory->retrieve(self::CONTEXT_AST, function() {
				$parser = new Parser(new Emulative);
				$stmts = $parser->parse($this->_file->getSource());
				return $stmts;
			});
		}

		/**
		 * @return File
		 */
		public function getFile() {
			return $this->_file;
		}
	}
