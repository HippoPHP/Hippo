<?php

	namespace Hippo\Config;

	use Hippo\Exception\BadConfigKeyException;

	class Config {
		private $array;

		public function __construct(array $array = []) {
			$this->array = $this->_normalizeArray($array);
		}

		/**
		 * @param string $key
		 * @throws BadConfigKeyException
		 * @return mixed
		 */
		public function get($key, $defaultValue = null) {
			if (func_num_args() === 1) {
				$current = &$this->_navigateToKey($key, false);
			} else {
				try {
					$current = &$this->_navigateToKey($key, false);
				} catch (BadConfigKeyException $e) {
					return $defaultValue;
				}
			}
			return is_array($current)
				? new self($current)
				: $current;
		}

		/**
		 * @param string $key
		 * @param mixed $value
		 * @return void
		 */
		public function set($key, $value) {
			$current = &$this->_navigateToKey($key, true);
			$current = is_array($value)
				? $this->_normalizeArray($value)
				: $value;
		}

		/**
		 * @param string $key
		 * @return void
		 */
		public function remove($key) {
			$current = &$this->_navigateToKey($key, false);
			$current = null;
		}

		/**
		 * @param mixed[] $array
		 * @return mixed[]
		 */
		private function _normalizeArray(array $array) {
			$output = [];
			foreach ($array as $key => $value) {
				$output[$this->_normalizeKey($key)] = is_array($value)
					? $this->_normalizeArray($value)
					: $value;
			}
			return $output;
		}

		/**
		 * @param string $key
		 * @return string
		 */
		private function _normalizeKey($key) {
			return trim(str_replace('_', '', strtolower($key)));
		}

		/**
		 * @param string $key
		 * @param boolean $createSections
		 * @throws BadConfigKeyException
		 * @return mixed reference to branch under given key
		 */
		private function &_navigateToKey($key, $createSections) {
			$current = &$this->array;
			foreach (explode('.', $key) as $key) {
				if (!is_array($current)) {
					if ($createSections) {
						$current = [];
					} else {
						throw new BadConfigKeyException('Trying to access child of scalar value');
					}
				}

				if (!isset($current[$this->_normalizeKey($key)])) {
					if ($createSections) {
						$current[$this->_normalizeKey($key)] = [];
					} else {
						throw new BadConfigKeyException('Trying to access a node that doesn\'t exist');
					}
				}

				$current = &$current[$this->_normalizeKey($key)];
			}
			return $current;
		}
	}
