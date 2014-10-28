<?php 

	namespace PHPCheckstyle\PHPCheckstyle;

	use PHPCheckstyle\Exception;
	use SeekableIterator;
	use SplDoublyLinkedList;

	/**
	 * File class.
	 */
	class File extends SplDoublyLinkedList implements SeekableIterator {
		/**
		 * Filename.
		 * @var string
		 */
		protected $filename;

		/**
		 * Source code within the file.
		 * @var string
		 */
		protected $source;

		/**
		 * Encoding of the file.
		 * @var string
		 */
		protected $encoding;

		/**
		 * Lines of the file.
		 * @var array
		 */
		protected $lines = array();

		/**
		 * Violations held against the file.
		 * @var array
		 */
		protected $violations = array();

		/**
		 * End of line character.
		 * @var string
		 */
		protected $eolChar = '';

		/**
		 * Create a new file representation.
		 * @param string $filename
		 * @param string $source
		 * @param string $encoding
		 * @return File
		 */
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

			return $this;
		}

		/**
		 * Return the filename.
		 * @return string
		 */
		public function getFilename() {
			return $this->filename;
		}

		/**
		 * Return the source of the file.
		 * @return string
		 */
		public function getSource() {
			return $this->source;
		}

		/**
		 * Return the individual array of lines.
		 * @return array
		 */
		public function getLines() {
			return $this->lines;
		}

		/**
		 * Return the file encoding.
		 * @return string
		 */
		public function getEncoding() {
			return $this->encoding;
		}

		/**
		 * Adds a new Violation to the file.
		 * @param Violation $violation
		 */
		public function addViolation(Violation $violation) {
			$this->violations[] = $violation;
		}

		/**
		 * Return all of the violations on the file.
		 * Violations are sorted on a line/column basis.
		 * @return array
		 */
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
