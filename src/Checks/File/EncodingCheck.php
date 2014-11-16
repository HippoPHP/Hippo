<?php

	namespace HippoPHP\Hippo\Checks\Naming;

	use \HippoPHP\Hippo\Violation;
	use \HippoPHP\Hippo\CheckContext;
	use \HippoPHP\Hippo\Config\Config;
	use \HippoPHP\Hippo\Checks\AbstractCheck;
	use \HippoPHP\Hippo\Checks\CheckInterface;

	class EncodingCheck extends AbstractCheck implements CheckInterface {
		/**
		 * File encoding to check for.
		 * @var string
		 */
		protected $encoding = 'UTF-8';

		public function setEncodingType($encoding) {
			$this->encoding = $encoding;
		}

		/**
		 * @return string
		 */
		public function getConfigRoot() {
			return 'file.encoding';
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

			$this->setEncodingType($config->get('encoding', $this->encoding));

			if (!mb_check_encoding($file->getSource(), $this->encoding)) {
				$this->addViolation(
					$file,
					$token->getLine(),
					$token->getColumn(),
					sprintf(
						'File encoding should be %s',
						$this->encoding
					),
					Violation::SEVERITY_INFO
				);
			}
		}
	}
