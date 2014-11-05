<?php

	namespace HippoPHP\Hippo\Reporters;

	use \HippoPHP\Hippo\FileSystem;
	use \HippoPHP\Hippo\CheckResult;

	/**
	 * CLI Reporter.
	 * @package Hippo
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	class CLIReporter implements ReporterInterface {
		private $_firstFile;
		private $_fileSystem;

		/**
		 * @param FileSystem $fileSystem
		 */
		public function __construct(FileSystem $fileSystem) {
			$this->_fileSystem = $fileSystem;
		}

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::start()
		 */
		public function start() {
			$this->_firstFile = true;
		}

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::addCheckResult()
		 * @param CheckResult $checkResult
		 */
		public function addCheckResult(CheckResult $checkResult) {
			$violations = $checkResult->getViolations();

			if (!$violations) {
				return;
			}

			if ($this->_firstFile) {
				$this->_firstFile = false;
			} else {
				$this->_write(PHP_EOL);
			}

			$this->_write($checkResult->getFile()->getFilename() . ':' . PHP_EOL);
			$this->_write(str_repeat('-', 80) . PHP_EOL);

			foreach ($violations as $violation) {
				$this->_write('Line ' . $violation->getLine());

				if ($violation->getColumn() > 0) {
					$this->_write(':' . $violation->getColumn());
				}

				$this->_write(' ('. $violation->getSeverityName() . ') : ');
				$this->_write($violation->getMessage() . PHP_EOL);
			}

			$this->_write(PHP_EOL);
			flush();
		}

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::finish()
		 */
		public function finish() {
		}

		private function _write($content) {
			return $this->_fileSystem->putContent('php://stdout', $content);
		}
	}
