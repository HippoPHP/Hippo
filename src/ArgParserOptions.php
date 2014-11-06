<?php

	namespace HippoPHP\Hippo;

	class ArgParserOptions {
		/**
		 * @var string[]
		 */
		private $_flags = [];

		public function markFlag($argName) {
			$this->_flags[] = $argName;
		}

		public function isFlag($argName) {
			return in_array($argName, $this->_flags);
		}
	}
