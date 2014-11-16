<?php

	namespace HippoPHP\Hippo\Checks\Line;

	use \HippoPHP\Hippo\CheckContext;
	use \HippoPHP\Hippo\Checks\AbstractCheck;
	use \HippoPHP\Hippo\Checks\CheckInterface;
	use \HippoPHP\Hippo\Config\Config;

	/**
	 * Checks the open tag.
	 */
	class MustEndWithCloseTagCheck extends AbstractCheck implements CheckInterface {
		/**
		 * @return string
		 */
		public function getConfigRoot() {
			return 'file.end_tag';
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
			$tokens->end();
			$endToken = $tokens->current();
			if (count($file) > 0 && !$endToken->isType(T_CLOSE_TAG)) {
				$this->addViolation($file, $endToken->getLine(), 0, 'Files must end with a closing tag.');
			}
			$tokens->rewind(); // Without this, the token list seems to go weird.
		}

	}
