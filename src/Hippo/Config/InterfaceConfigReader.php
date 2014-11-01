<?php

	namespace Hippo\Config;

	interface InterfaceConfigReader {
		/**
		 * @param string $filename
		 * @return array
		 */
		function loadFromFile($filename);
	}
