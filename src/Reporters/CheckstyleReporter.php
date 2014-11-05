<?php

	namespace HippoPHP\Hippo\Reporters;

	use \HippoPHP\Hippo\CheckResult;
	use \XmlWriter;

	/**
	 * Checkstyle Reporter.
	 * @package Hippo
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	class CheckstyleReporter implements ReporterInterface {
		/**
		 * XML Writer.
		 * @var XMLWriter
		 */
		protected $writer;

		/**
		 * @var string
		 */
		protected $filename;

		/**
		 * Creates a new writer object, ready to write XML.
		 * @param string $filename
		 */
		public function __construct($filename) {
			$this->filename = $filename;
		}

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::start()
		 */
		public function start() {
			$this->writer = new XMLWriter();
			$this->writer->openUri($filename);
			$this->writer->setIndent(true);
			$this->writer->setIndentString('    ');

			$this->writer->startDocument('1.0', 'UTF-8');
			$this->writer->startElement('checkstyle');
			$this->writer->writeAttribute('version', '5.5');
		}

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::addCheckResult()
		 * @param CheckResult $checkResult
		 */
		public function addCheckResult(CheckResult $checkResult) {
			$this->writer->startElement('file');
			$this->writer->writeAttribute('name', $checkResult->getFile()->getFilename());

			foreach ($checkResult->getViolations() as $violation) {
				$this->writer->startElement('error');

				$this->writer->writeAttribute('line', $violation->getLine());

				if ($violation->getColumn() > 0) {
					$this->writer->writeAttribute('column', $violation->getColumn());
				}

				$this->writer->writeAttribute('severity', $violation->getSeverity());
				$this->writer->writeAttribute('message', $violation->getMessage());
				$this->writer->writeAttribute('source', $violation->getSource());

				$this->writer->endElement();
			}

			$this->writer->endElement();
		}

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::finish()
		 */
		public function finish() {
			$this->writer->endElement();
			$this->writer->endDocument();
		}
	}
