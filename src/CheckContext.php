<?php

	namespace HippoPHP\Hippo;

	use \HippoPHP\Hippo\Exception;
	use \HippoPHP\Hippo\File;
	use \HippoPHP\Tokenizer\Tokenizer;

	class CheckContext {
		const CONTEXT_TOKEN_LIST = 'tokenList';
		const CONTEXT_AST = 'ast';

		/**
		 * @var array<int,*>
		 */
		private $_cache = [];

		/**
		 * @var File
		 */
		private $_file;

		/**
		 * @param File $file
		 */
		public function __construct(File $file) {
			$this->_file = $file;
		}

		/**
		 * @return string[]
		 */
		public function getTokenList() {
			return $this->_lazyGet(self::CONTEXT_TOKEN_LIST, function() {
				$tokenizer = new Tokenizer();
				$tokenList = $tokenizer->tokenize($this->_file->getSource());
				return $tokenList;
			});
		}

		/**
		 * @return mixed
		 */
		public function getSyntaxTree() {
			return $this->_lazyGet(self::CONTEXT_AST, function() {
				throw new BadMethodCallException('Not implemented');
			});
		}

		/**
		 * @return File
		 */
		public function getFile() {
			return $this->_file;
		}

		/**
		 * @param mixed $cacheKey
		 * @param callable $factory
		 * @return mixed
		 */
		private function _lazyGet($cacheKey, callable $factory) {
			if (!isset($this->_cache[$cacheKey])){
				$this->_cache[$cacheKey] = $factory();
			}
			return $this->_cache[$cacheKey];
		}
	}
