<?php

	namespace Hippo;

	abstract class AbstractConfigReader {
		abstract public function deserialize($config);
	}
