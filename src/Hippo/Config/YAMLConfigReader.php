<?php 

	namespace Hippo\Config;

	use Symfony\Component\Yaml\Parser as YamlParser;

	class YAMLConfigReader extends AbstractConfigReader {
		protected $parser;

		public function __construct() {
			$this->parser = new YamlParser;
		}

		public function deserialize($config) {
			$config = $this->parser->parse($config);

			// If we're extending another standard, use it as a base.
			if (isset($config['extends'])) {
				$baseConfigName = $config['extends'];
				// TODO: This feels ugly. Better way?
				$baseConfigSrc = file_get_contents(__DIR__ . '/../standards/' . $baseConfigName . '.yml');
				$baseConfig = $this->parser->parse($baseConfigSrc);

				return array_merge($baseConfig, $config);
			} else {
				return $config;
			}
		}
	}