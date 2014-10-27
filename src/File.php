<?php 

	namespace PHPCheckstyle\PHPCheckstyle;

	use PHPCheckstyle\Exception;
	use SeekableIterator;
	use SplDoublyLinkedList;

	class File extends SplDoublyLinkedList implements SeekableIterator {
		protected $filename;

		protected $source;

		protected $encoding;

		protected $lines = array();

		protected $violations = array();

		protected $eolChar = '';

		public function __construct($filename = NULL, $source = NULL, $encoding = 'UTF-8') {
			$this->filename = $filename;
			$this->source = NULL;
			$this->encoding = $encoding;

			preg_match_all('((?<content>.*?)(?<ending>\n|\r\n?|$))', $source, $matches, PREG_SET_ORDER);

			foreach ($matches as $index => $line) {
				$this->lines[$index + 1] = array(
					'content' => $line['content'],
					'ending' => $line['ending']
				);
			}
		}

		public function getFilename() {
			return $this->filename;
		}

		public function getSource() {
			return $this->source;
		}

		public function getLines() {
			return $this->lines;
		}

		public function getEncoding() {
			return $this->encoding;
		}

		public function addViolation(Violation $violation) {
			$this->violations[] = $violation;
		}

		public function getViolations() {
			usort($this->violations, function(Violation $a, Violation $b) {
				if ($a->getLine() === $b->getLine()) {
					if ($a->getColumn() === $b->getColumn()) {
						return 0;
					}

					return ($a->getColumn() < $b->getColumn() ? -1 : 1);
				}

				return ($a->getLine() < $b->getLine() ? -1 : 1);
			});

			return $this->violations;
		}

		/**
		 * Seek to a specific token type.
		 *
		 * Returns true on success and false if the token can not be found. Seeking
		 * starts from current element. If the current token matches the given type,
		 * the position is not changed. In case a stopper is supplied, the seeking
		 * will stop at the given token.
		 *
		 * @param  mixed   $type
		 * @param  boolean $backwards
		 * @param  mixed   $stopper
		 * @return boolean
		 */
		public function seekTokenType($type, $backwards = false, $stopper = null) {
			$currentPosition = $this->key();

			while ($this->valid()) {
				$current = $this->current()->getType();

				if (
					$stopper !== null && (is_array($stopper)
					&& in_array($current, $stopper)) || $current === $stopper
				) {
					break;
				}

				if ((is_array($type) && in_array($current, $type)) || $current === $type) {
					return true;
				}

				if ($backwards) {
					$this->prev();
				} else {
					$this->next();
				}
			}

			$this->seek($currentPosition);

			return false;
		}

		/**
		 * Seek to the next line.
		 *
		 * @return boolean
		 */
		public function seekNextLine() {
			$line = $this->current()->getLine();

			while (true) {
				$this->next();

				if (!$this->valid()) {
					return false;
				} elseif ($this->current()->getLine() > $line) {
					return true;
				}
			}
		}

		/**
		 * seek(): defined by SeekableIterator interface.
		 *
		 * @see    SeekableIterator::seek()
		 * @param  integer $position
		 * @return void
		 */
		public function seek($position) {
			$this->rewind();
			$current = 0;

			while ($current < $position) {
				if (!$this->valid()) {
					throw new Exception\OutOfBoundsException('Invalid seek position');
				}

				$this->next();
				$current++;
			}
		}
	}