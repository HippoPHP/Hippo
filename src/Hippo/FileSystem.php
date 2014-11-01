<?php

	namespace Hippo;

	use Hippo\Exception\FileNotFoundException;
	use Hippo\Exception\FileNotWritableException;
	use Hippo\Exception\FileNotReadableException;

	class FileSystem {
		/**
		 * @param string $path
		 * @return string
		 */
		public function getContent($path) {
			if (!file_exists($path)) {
				throw new FileNotFoundException($path);
			}
			if (is_dir($path) || !is_readable($path)) {
				throw new FileNotReadableException($path);
			}
			return file_get_contents($path);
		}

		/**
		 * @param string $path
		 * @param string $content
		 * @return void
		 */
		public function putContent($path, $content) {
			if (file_exists($path) and is_dir($path)) {
				throw new FileNotWritableException($path);
			}
			if (!is_writable(dirname($path))) {
				throw new FileNotWritableException($path);
			}
			file_put_contents($path, $content);
		}
	}
