<?php 

	namespace PHPCheckstyle\Reporter;

	use PHPCheckstyle\File;
	use XmlWriter;

	/**
	 * Checkstyle Reporter.
	 * @package PHPCheckstyle
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	class CheckstyleReporter extends ReportInterface {
		/**
		 * XML Writer.
		 * @var XMLWriter
		 */
		protected $writer;

		/**
		 * Creates a new writer object, ready to write XML.
		 * @param string $filename
		 */
		public function __construct($filename) {
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
		 * @see ReportInterface::addFile()
		 * @param File $file
		 */
		public function addFile(File $file) {
			$this->writer->startElement('file');
			$this->writer->writeAttribute('name', $file->getFilename());

			foreach ($file->getViolations() as $violation) {
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
		 * Closes the file handles.
		 * @return void
		 */
		public function __destruct() {
	       $this->writer->endElement();
	       $this->writer->endDocument();
	   }
	}