<?php

	namespace HippoPHP\Hippo\Checks\Comments;

	use \HippoPHP\Hippo\Violation;
	use \HippoPHP\Hippo\CheckContext;
	use \HippoPHP\Hippo\Config\Config;
	use \HippoPHP\Hippo\Checks\AbstractCheck;
	use \HippoPHP\Hippo\Checks\CheckInterface;
	use \HippoPHP\Hippo\Exception\BadConfigKeyException;

	class NoShellCommentsCheck extends AbstractCheck implements CheckInterface {
			/**
		 * @return string
		 */
		public function getConfigRoot() {
			return 'comments.no_shell_comments';
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
					$tokens->seekToType(T_COMMENT);

					$token = $tokens->current();

					if (strpos($token->getContent(), '#') === 0) {
						$this->addViolation(
							$file,
							$token->getLine(),
							$token->getColumn(),
							'Avoid using bash style comments.',
							Violation::SEVERITY_ERROR
						);
					}
				} while ($tokens->valid());
			} catch (\HippoPHP\Tokenizer\Exception\OutOfBoundsException $e) {
				// Ignore the exception, we're at the end of the file.
			}
		}
	}
