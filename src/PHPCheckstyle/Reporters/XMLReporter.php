<?php 

	namespace PHPCheckstyle\Reporter;

	use PHPCheckstyle\File;
	use XMLWriter;

	/**
	 * Array Reporter.
	 * @package PHPCheckstyle
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	class XMLReporter extends ReportInterface {
		/**
		 * XMLWriter
		 * @var XMLWriter
		 */
		protected $writer;
		
		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::addFile()
		 * @param File $file
		 */
		public function addFile(File $file) {
			
		}
	}
