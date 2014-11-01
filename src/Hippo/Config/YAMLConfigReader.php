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

		public function loadFromFile($filename) {
			$config = $this->parser->parse($this->fileSystem->getContent($filename));

			// If we're extending another standard, use it as a base.
			if (isset($config['extends'])) {
				$baseConfigName = $config['extends'];
				$baseConfigPath = dirname($filename) . DIRECTORY_SEPARATOR . $baseConfigName . '.yml';
				$baseConfig = $this->parser->parse($this->fileSystem->getContent($baseConfigPath));
				return array_merge($baseConfig, $config);
			} else {
				return $config;
			}
		}
	}
