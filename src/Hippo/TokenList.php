<?php

	namespace Hippo;

	use Hippo\Token;
	use SplDoublyLinkedList;

	/**
	 * Seekable container for token list.
	 * @package Hippo
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	class TokenList extends SplDoublyLinkedList {
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
