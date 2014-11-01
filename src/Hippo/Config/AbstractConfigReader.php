<?php

	namespace Hippo\Config;

	abstract class AbstractConfigReader {
		abstract public function deserialize($config);
	}
