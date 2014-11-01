<?php 

	namespace Hippo\Config;

	use Symfony\Component\Yaml\Parser as YamlParser;

	class YAMLConfigReader extends AbstractConfigReader {
		protected $parser;

		public function __construct() {
			$this->parser = new YamlParser;
		}

		public function deserialize($config) {
			return $this->parser->parse($config);
		}
	}