<?php

	namespace Hippo\Config;

	abstract class AbstractConfigReader {
		/**
		 * @param string $filename
		 * @return array
		 */
		abstract public function loadFromFile($filename);
	}
