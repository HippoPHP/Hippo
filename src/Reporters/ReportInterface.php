<?php 

	namespace PHPCheckstyle\Reporter;

	use PHPCheckstyle\File;

	/**
	 * Reporters should inherit from this.
	 */
	interface ReportInterface {
		/**
		 * Adds a file to the report.
		 * @param File $file
		 * @return void
		 */
		public function addFile(File $file);
	}