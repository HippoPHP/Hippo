<?php

	namespace Hippo\Checks\Line;

	use Hippo\Checks\AbstractCheck;
	use Hippo\Checks\CheckInterface;
	use Hippo\Config\Config;
	use Hippo\File;
	use Hippo\Violation;

	class MaxLength extends AbstractCheck implements CheckInterface {
		/**
		 * Limit for emitting errors.
		 * @var integer
		 */
		protected $errorLimit = 80;

		/**
		 * Limit for emitting warnings.
		 * @var integer
		 */
		protected $warningLimit = null;

		/**
		 * Limit for emitting infos.
		 * @var integer
		 */
		protected $infoLimit = null;

		/**
		 * Defines how many spaces a tab takes up.
		 * @var integer
		 */
		protected $tabExpand = 4;

		/**
		 * Sets the error line length limit.
		 * @param int $length
		 * @return MaxLength
		 */
		public function setErrorLimit($length) {
			$this->errorLimit = ((int) $length) ?: null;
			return $this;
		}

		/**
		 * Sets the warning line length limit.
		 * @param int $length
		 * @return MaxLength
		 */
		public function setWarningLimit($length) {
			$this->warningLimit = ((int) $length) ?: null;
			return $this;
		}

		/**
		 * Sets the info line length limit.
		 * @param int $length
		 * @return MaxLength
		 */
		public function setInfoLimit($length) {
			$this->infoLimit = ((int) $length) ?: null;
			return $this;
		}

		/**
		 * Sets how many spaces make up a tab.
		 * @param int $size
		 * @return MaxLength
		 */
		public function setTabExpand($size) {
			$this->tabExpand = (int) $size;
			return $this;
		}

		/**
		 * checkFile(): defined by CheckInterface.
		 * @see CheckInterface::checkFile()
		 * @param File $file
		 * @param Config $config
		 * @return void
		 */
		public function checkFile(File $file, Config $config) {
			$this->setErrorLimit($config->get('error_limit', $this->errorLimit));
			$this->setWarningLimit($config->get('warning_limit', $this->warningLimit));
			$this->setInfoLimit($config->get('info_limit', $this->infoLimit));
			$this->setTabExpand($config->get('tab_expand', $this->tabExpand));

			foreach ($file->getLines() as $line => $data) {
				$lineLength = iconv_strlen(
					str_replace("\t", str_repeat(' ', $this->tabExpand), $data['content']),
					$file->getEncoding()
				);

				$violationLimit = null;
				$severity = null;

				if ($this->errorLimit !== null && $lineLength > $this->errorLimit) {
					$violationLimit = $this->errorLimit;
					$severity       = Violation::SEVERITY_ERROR;
				} elseif ($this->warningLimit !== null && $lineLength > $this->warningLimit) {
					$violationLimit = $this->warningLimit;
					$severity       = Violation::SEVERITY_WARNING;
				} elseif ($this->infoLimit !== null && $lineLength > $this->infoLimit) {
					$violationLimit = $this->infoLimit;
					$severity       = Violation::SEVERITY_INFO;
				}

				if ($violationLimit !== null) {
					$this->addViolation(
						$file,
						$line,
						0,
						sprintf('Line is longer than %d characters.', $violationLimit),
						$severity
					);
				}
			}
		}

		/**
		 * @return string
		 */
		public function getConfigRoot() {
			return 'file.max_line_length';
		}
	}
