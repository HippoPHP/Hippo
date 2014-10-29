<?php 

	namespace PHPCheckstyle;

	use PHPCheckstyle\Exception;
	use SeekableIterator;
	use SplDoublyLinkedList;
	use Countable;

	/**
	 * Result of running a check.
	 */
	class CheckResult implements Countable {
		/**
		 * Was modified since last violation retrieval?
		 * @var boolean
		 */
		protected $violationsDirty;

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
		public function addViolation(Violation $violation) {
			$this->violations[] = $violation;
			$this->violationsDirty = true;
		}

		public function getViolations() {
			$this->_processViolationsIfDirty();
			return $this->violations;
		}

		/**
		 * Counts how many violations are in the result.
		 * @return int
		 * @see Countable::count()
		 */
		public function count() {
			return count($this->violations);
		}

		private function _processViolationsIfDirty() {
			if ($this->violationsDirty) {
				$this->_sortViolations();
				$this->violationsDirty = false;
			}
		}

		private function _sortViolations() {
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
