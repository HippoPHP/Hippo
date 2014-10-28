<?php 

	namespace PHPCheckstyle;

	use PHPCheckstyle\Exception;
	use SeekableIterator;
	use SplDoublyLinkedList;

	/**
	 * Result of running a check.
	 */
	class CheckResult {
		/**
		 * Violations held against the file.
		 * @var array
		 */
		protected $violations = array();

		/**
		 * Returns whether check succeeded.
		 * @return bool
		 */
		public function hasSucceeded() {
			return empty($this->violations);
		}

		public function hasFailed() {
			return empty($this->violations) === FALSE;
		}

		/**
		 * Return all of the violations on the file.
		 * Violations are sorted on a line/column basis.
		 * @return array
		 */
		public function getViolations() {
			return $this->violations;
		}

		public function addViolation(Violation $violation) {
			$this->violations[] = $violation;

			usort($this->violations, function(Violation $a, Violation $b) {
				if ($a->getLine() === $b->getLine()) {
					if ($a->getColumn() === $b->getColumn()) {
						return 0;
					}

					return ($a->getColumn() < $b->getColumn() ? -1 : 1);
				}

				return ($a->getLine() < $b->getLine() ? -1 : 1);
			});
		}
	}
