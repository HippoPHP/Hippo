<?php

	namespace HippoPHP\Hippo\Config;

	use \HippoPHP\Hippo\FileSystem;
	use \Symfony\Component\Yaml\Parser as YamlParser;

	class YAMLConfigReader implements ConfigReaderInterface {
		protected $parser;
		protected $fileSystem;

		/**
		 * @param FileSystem $fileSystem
		 */
		public function __construct(FileSystem $fileSystem) {
			$this->parser = new YamlParser;
			$this->fileSystem = $fileSystem;
		}

		/**
		 * @param string $filename
		 * @return Config
		 */
		public function loadFromFile($filename) {
			$config = $this->parser->parse($this->fileSystem->getContent($filename));

			$included = [$this->_normalizeConfigName($filename)];

			// If we're extending another standard, use it as a base.
			while (isset($config['extends'])) {
				$baseConfigName = $config['extends'];
				$baseConfigPath = dirname($filename) . DIRECTORY_SEPARATOR . $baseConfigName . '.yml';
				$baseConfig = $this->parser->parse($this->fileSystem->getContent($baseConfigPath));
				unset($config['extends']);

				$config = $this->_mergeRecursive($baseConfig, $config);

				if (isset($config['extends'])) {
					if (in_array($this->_normalizeConfigName($config['extends']), $included)) {
						// Avoid circular dependencies
						unset($config['extends']);
					} else {
						$included[] = $this->_normalizeConfigName($config['extends']);
					}
				}
			}

			return new Config($config);
		}

		/**
		 * Normalizes a configuration filename
		 * @param  string $name
		 * @return string
		 */
		private function _normalizeConfigName($name) {
			return trim(basename(strtolower($name), '.yml'));
		}

		/**
		 * @param array<*,*> $array1
		 * @param array<*,*> $array2
		 * @return array<*,*>
		 */
		private function _mergeRecursive($array1, $array2) {
			$result = [];
			foreach (array_merge(array_keys($array1), array_keys($array2)) as $key) {
				if (!isset($array1[$key])) {
					$result[$key] = $array2[$key];
					continue;
				}

				if (!isset($array2[$key])) {
					$result[$key] = $array1[$key];
					continue;
				}

				if (is_array($array1[$key]) || is_array($array2[$key])) {
					if (!is_array($array1[$key]) || !is_array($array2[$key])) {
						throw new \Exception('Cannot merge a scalar with an array');
					}
					$result[$key] = $this->_mergeRecursive($array1[$key], $array2[$key]);
					continue;
				}

				$result[$key] = $array2[$key];
			}
			return $result;
		}
	}
