<?php

	namespace Hippo\Config;

	use Hippo\FileSystem;
	use Symfony\Component\Yaml\Parser as YamlParser;

	class YAMLConfigReader implements InterfaceConfigReader {
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

				$config = array_merge($baseConfig, $config);

				if (isset($config['extends'])) {
					if (in_array($this->_normalizeConfigName($config['extends']), $included)) {
						// Avoid circular dependencies
						unset($config['extends']);
					} else {
						$included[] = $this->_normalizeConfigName($config['extends']);
					}
				}
			}

			return new config($config);
		}

		private function _normalizeConfigName($name) {
			return trim(basename(strtolower($name), '.yml'));
		}
	}
