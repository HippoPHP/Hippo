<?php

	namespace HippoPHP\Hippo\Config;

	interface ConfigReaderInterface {
		/**
		 * @param string $filename
		 * @return Config
		 */
		function loadFromFile($filename);
	}
