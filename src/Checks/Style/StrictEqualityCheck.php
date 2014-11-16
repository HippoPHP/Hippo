<?php

	namespace HippoPHP\Hippo\Checks\Naming;

	use \HippoPHP\Hippo\Violation;
	use \HippoPHP\Hippo\CheckContext;
	use \HippoPHP\Hippo\Config\Config;
	use \HippoPHP\Hippo\Checks\AbstractCheck;
	use \HippoPHP\Hippo\Checks\CheckInterface;
	use \HippoPHP\Hippo\Exception\BadConfigKeyException;

	class StrictEqualityCheck extends AbstractCheck implements CheckInterface {
		/**
		 * @return string
		 */
		public function getConfigRoot() {
			return 'style.strict_equality';
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

			try {
				do {
					// Jump us to the next token we want to check.
					$tokens->seekToType(T_IS_EQUAL);

					$token = $tokens->current();

					$this->addViolation(
						$file,
						$token->getLine(),
						$token->getColumn(),
						'Avoid the use of the equality operator `==`.',
						Violation::SEVERITY_WARNING
					);
				} while ($tokens->valid());
			} catch (\HippoPHP\Tokenizer\Exception\OutOfBoundsException $e) {
				// Ignore the exception, we're at the end of the file.
			}
		}
	}
