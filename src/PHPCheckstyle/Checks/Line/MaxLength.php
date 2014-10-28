<?php 

	namespace PHPCheckstyle\Check\Line;

	use PHPCheckstyle\File;
	use PHPCheckstyle\Violation;
	use PHPCheckstyle\Check\AbstractCheck;
	use PHPCheckstyle\Check\FileCheckInterface;

	class MaxLength extends AbstractCheck implements FileCheckInterface {
		/**
		 * Limit for emitting errors.
		 * @var integer
		 */
		protected $errorLimit = 80;

		/**
		 * Limit for emitting warnings.
		 * @var integer
		 */
		protected $warningLimit = NULL;

		/**
		 * Limit for emitting infos.
		 * @var integer
		 */
		protected $infoLimit = NULL;

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
			$this->errorLimit = ((int) $length) ?: NULL;
			return $this;
		}

		/**
		 * Sets the warning line length limit.
		 * @param int $length
		 * @return MaxLength
		 */
		public function setWarningLimit($length) {
			$this->warningLimit = ((int) $length) ?: NULL;
			return $this;
		}

		/**
		 * Sets the info line length limit.
		 * @param int $length
		 * @return MaxLength
		 */
		public function setInfoLimit($length) {
			$this->infoLimit = ((int) $length) ?: NULL;
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
		 * visitFile(): defined by FileCheckInterface.
		 * @see FileCheckInterface::visitFile()
		 * @param File $file
		 * @return void
		 */
		public function visitFile(File $file) {
			foreach ($file->getLines() as $line => $data) {
				$lineLength = iconv_strlen(
					str_replace("\t", str_repeat(' ', $this->tabExpand), $data['content']),
					$file->getEncoding()
				);

				$violationLimit = NULL;
				$severity = NULL;

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

				if ($violationLimit !== NULL) {
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
	}
