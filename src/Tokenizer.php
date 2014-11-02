<?php

	namespace HippoPHP\Hippo;

	use \HippoPHP\Hippo\File;
	use \HippoPHP\Hippo\Token;
	use \HippoPHP\Hippo\TokenList;

	/**
	 * Tokenizes a file.
	 * @package Hippo
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	class Tokenizer {
		private $_blockIndent = array('(', '{', T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES);
		private $_blockOutdent = array(')', '}');

		/**
		 * Performs tokenization on the file
		 * @param  File $file
		 * @return TokenList
		 */
		public function tokenize(File $file) {
			$line   = 1;
			$column = 1;
			$level  = 0;

			$namespaceFound = false;
			$namespace      = null;
			$namespaceLevel = null;

			$tokenList = new TokenList;
			$tokens = token_get_all($file->getSource());

			foreach ($tokens as $token) {
				$type = is_array($token) ? $token[0] : $token;
				$lexeme = is_array($token) ? $token[1] : $token;

				$token = new Token($type, $lexeme, $line, $column);

				if ($token->hasNewline()) {
					$line += $token->getNewLineCount();
					$column = 1 + $token->getTrailingLineLength();
				} else {
					$column += $token->getLength();
				}

				// Namespace handling.
				if ($type === T_NAMESPACE) {
					$namespaceFound = true;
				} elseif ($namespaceFound) {
					if (in_array($type, array(T_STRING, T_NS_SEPARATOR))) {
						$namespace .= $lexeme;
					} elseif ($type === ';') {
						$namespaceFound = false;
					} elseif ($type === '{') {
						$namespaceFound = false;
						$namespaceLevel = $level;
					}
				} elseif ($type === '}' && ($level - 1) === $namespaceLevel) {
					$namespace      = null;
					$namespaceLevel = null;
				} elseif (!$namespaceFound && $namespace !== null) {
					$token->setNamespace($namespace);
				}

				$token->setLevel($this->_blockDent($type, $level));

				$tokenList[] = $token;
			}

			return $tokenList;
		}

		private function _blockDent($type, &$level) {
			if (in_array($type, $this->_blockIndent)) {
				$level++;
			} elseif (in_array($type, $this->_blockOutdent)) {
				$level--;
			}
			return $level;
		}
	}
