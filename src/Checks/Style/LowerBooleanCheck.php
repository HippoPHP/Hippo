<?php

	namespace HippoPHP\Hippo\Checks\Style;

	use \HippoPHP\Hippo\Violation;
	use \HippoPHP\Hippo\CheckContext;
	use \HippoPHP\Hippo\Config\Config;
	use \HippoPHP\Hippo\Checks\AbstractCheck;
	use \HippoPHP\Hippo\Checks\CheckInterface;

	class LowerBooleanCheck extends AbstractCheck implements CheckInterface {
		protected $keywords = [
			'null',
			'true',
			'false'
		];

		/**
		 * @return string
		 */
		public function getConfigRoot() {
			return 'style.lower_bools';
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
					$tokens->seekToType(T_STRING);
					$token = $tokens->current();

					$tokenContent = $token->getContent();
					$lowerContent = strtolower($tokenContent);
					if (in_array($lowerContent, $this->keywords)) {
						if ($tokenContent !== $lowerContent) {
							$this->addViolation(
								$file,
								$token->getLine(),
								$token->getColumn(),
								sprintf(
									'`%s` should be in lowercase.',
									$tokenContent
								),
								Violation::SEVERITY_INFO
							);
						}
					}
				} while ($tokens->valid());
			} catch (\HippoPHP\Tokenizer\Exception\OutOfBoundsException $e) {
				// Ignore the exception, we're at the end of the file.
			}
		}
	}