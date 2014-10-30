<?php 

	namespace Hippo\Check\Line;

	use Hippo\File;
	use Hippo\Violation;
	use Hippo\Check\AbstractCheck;
	use Hippo\Check\FileCheckInterface;

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
	}
