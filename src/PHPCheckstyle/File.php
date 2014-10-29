<?php 

	namespace PHPCheckstyle;

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
		 * Create a new file representation.
		 * @param string $filename
		 * @param string $source
		 * @param string $encoding
		 * @return File
		 */
		public function __construct($filename = NULL, $source = NULL, $encoding = 'UTF-8') {
			$this->filename = $filename;
			$this->source = $source;
			$this->encoding = $encoding;

			$this->lines = $this->_buildLinesFromSource($source);

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

		/**
		 * @param string $source
		 * @return array
		 */
		private function _buildLinesFromSource($source) {
			$eols = array("\r\n", "\n", "\r");
			usort($eols, function($eol1, $eol2) { return strlen($eol2) - strlen($eol1); });

			$lines = array();
			while (!empty($source)) {
				$eolFound = false;
				foreach ($eols as $eol) {
					$index = strpos($source, $eol);
					if ($index !== false) {
						$lines[] = substr($source, 0, $index + strlen($eol));
						$source = substr($source, $index + strlen($eol));
						$eolFound = true;
						break;
					}
				}
				if (!$eolFound || $source === false) {
					$lines[] = $source;
					break;
				}
			}
			return $lines;
		}
	}
