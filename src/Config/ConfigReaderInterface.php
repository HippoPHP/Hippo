<?php

	namespace HippoPHP\Hippo\Config;

	interface ConfigReaderInterface {
		/**
		 * @param string $filename
		 * @return Config
		 */
		function loadFromFile($filename);

		/**
		 * @param string $string
		 * @return Config
		 */
		function loadFromString($string);
	}
