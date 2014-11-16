<?php

	namespace HippoPHP\Hippo\Checks\Naming;

	use \HippoPHP\Hippo\Violation;
	use \HippoPHP\Hippo\CheckContext;
	use \HippoPHP\Hippo\Config\Config;
	use \HippoPHP\Hippo\Checks\AbstractCheck;
	use \HippoPHP\Hippo\Checks\CheckInterface;
	use \HippoPHP\Hippo\Exception\BadConfigKeyException;

	class PrivateVariableNamingCheck extends AbstractCheck implements CheckInterface {
		private $_pattern = "/^_[a-z][a-zA-Z0-9]*$/";

		public function setPattern($pattern) {
			$this->_pattern = $pattern;
		}

		/**
		 * @return string
		 */
		public function getConfigRoot() {
			return 'style.private_variable_naming';
		}

		/**
		 * checkFileInternal(): defined by AbstractCheck.
		 * @see AbstractCheck::checkFileInternal()
		 * @param CheckContext $checkContext
		 * @param Config $config
		 * @return void
		 */
		protected function checkFileInternal(CheckContext $checkContext, Config $config) {
			$file = $checkContext->getFile();
			$tokens = $checkContext->getTokenList();

			$this->setPattern($config->get('pattern', $this->_pattern));

			try {
				do {
					// Jump us to the next token we want to check.
					$tokens->seekToType(T_PRIVATE);
					$token = $tokens->next(2)->current();

					if ($token->isType(T_VARIABLE)) {
						if (preg_match($this->_pattern, $token->getContent())) {
							$this->addViolation(
								$file,
								$token->getLine(),
								$token->getColumn(),
								sprintf(
									'Private variables should follow a `%s` pattern',
									addslashes($this->_pattern)
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
