<?php

	namespace Hippo\Config;

	interface InterfaceConfigReader {
		/**
		 * @param string $filename
		 * @return Config
		 */
		function loadFromFile($filename);
	}
