<?php 

	namespace Hippo;

	use Hippo\File;
	use Hippo\Token;
	use Hippo\TokenList;

	/**
	 * Tokenizes a file.
	 * @package Hippo
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	class Tokenizer {
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

			foreach (token_get_all($file->getSource()) as $token) {
				if (is_array($token)) {
					$type   = $token[0];
					$lexeme = $token[1];
				} else {
					$type   = $token;
					$lexeme = $token;
				}

				$token = new Token($type, $lexeme, $line, $column);

				if ($token->hasNewline()) {
					$line   += $token->getNewLineCount();
					$column  = 1 + $token->getTrailingLineLength();
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

				// Block level increment.
				if (in_array($type, array('(', '{', T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES))) {
					$level++;
				} elseif (in_array($type, array(')', '}'))) {
					$level--;
				}

				$token->setLevel($level);

				$tokenList[] = $token;
			}

			return $tokenList;
		}
	}
