<?php 

	namespace PHPCheckstyle\Reporter;

	/**
	 * Reporters should inherit from this.
	 */
	abstract class Reporter {
		public function report();
	}