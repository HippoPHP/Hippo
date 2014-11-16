<?php

	namespace HippoPHP\Hippo\Checks\Naming;

	use \HippoPHP\Hippo\CheckContext;
	use \HippoPHP\Hippo\Config\Config;
	use \HippoPHP\Hippo\Checks\AbstractCheck;
	use \HippoPHP\Hippo\Checks\CheckInterface;
	use \HippoPHP\Hippo\Exception\BadConfigKeyException;

	class UseNamespaceConditionCheck extends AbstractCheck implements CheckInterface {
		/**
		 * @return string
		 */
		public function getConfigRoot() {
			return 'style.fully_qualified_namespaces';
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
					$tokens->seekToType(T_USE);

					// We should now be at `use`.
					// Now we need to skip the whitespace.
					$tokens->next();
					$tokens->next();

					// Now if the next token does not equal T_NS_SEPARATOR we are not fully qualified.
					$token = $tokens->current();
					if (!$token->isType(T_NS_SEPARATOR)) {
						$this->addViolation(
							$file,
							$token->getLine(),
							$token->getColumn(),
							'Use fully qualified namespaces.'
						);
					}
				} while ($tokens->valid());
			} catch (\HippoPHP\Tokenizer\Exception\OutOfBoundsException $e) {
				// Ignore the exception, we're at the end of the file.
			}
		}
	}
