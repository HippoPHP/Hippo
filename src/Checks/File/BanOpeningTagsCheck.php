<?php

	namespace HippoPHP\Hippo\Checks\Naming;

	use \HippoPHP\Hippo\CheckContext;
	use \HippoPHP\Hippo\Checks\AbstractCheck;
	use \HippoPHP\Hippo\Checks\CheckInterface;
	use \HippoPHP\Hippo\Config\Config;

	class BanOpeningTagsCheck extends AbstractCheck implements CheckInterface {
		/**
		 * @var array
		 */
		private $_bannedTags = [
			'<?',
			'<%'
		];

		/**
		 * @return string
		 */
		public function getConfigRoot() {
			return 'file.banned_opening_tags';
		}

		/**
		 * Sets the banned tags.
		 * @param array $tags
		 * @return BanOpeningTagsCheck
		 */
		public function setBannedTags($tags) {
			$this->_bannedTags = $tags;
			return $this;
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

			$this->setBannedTags($config->get('tags', $this->_bannedTags));

			if ($tokens->count() > 0) {
				$token = $tokens->current();
				if ($this->_checkOpeningTag($token)) {
					$this->addViolation(
						$file,
						trim($token->getLine()),
						trim($token->getColumn()),
						sprintf(
							'Do not use %s as an opening tag',
							$token->getContent()
						)
					);
				}
			}
		}

		/**
		 * Checks whether the tag matches the requirements.
		 * @return bool
		 */
		private function _checkOpeningTag($token) {
			$tokenContent = $token->getContent();
			return $token->isType(T_OPEN_TAG) && in_array($tokenContent, $this->_bannedTags);
		}
	}
