<?php

	namespace HippoPHP\Hippo;

	use \HippoPHP\Hippo\Exception\FileNotFoundException;
	use \HippoPHP\Hippo\Exception\FileNotWritableException;
	use \HippoPHP\Hippo\Exception\FileNotReadableException;

	use \RecursiveDirectoryIterator;
	use \RecursiveIteratorIterator;
	use \RegexIterator;

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
			if (file_exists($path) && is_dir($path)) {
				throw new FileNotWritableException($path);
			}
			if (!is_writable(dirname($path))) {
				throw new FileNotWritableException($path);
			}
			file_put_contents($path, $content);
		}

		/**
		 * @param string $path
		 * @param string $regex
		 * @return string[]
		 */
		public function getAllFiles($initialDirectory, $regex = null) {
			$directoryIterator = new RecursiveDirectoryIterator($initialDirectory, RecursiveDirectoryIterator::SKIP_DOTS);
			$flattenedIterator = new RecursiveIteratorIterator($directoryIterator);
			$iterator = $regex !== null
				? new RegexIterator($flattenedIterator, $regex)
				: $flattenedIterator;

			$output = [];
			foreach ($iterator as $i) {
				$output[] = $i->getRealpath();
			}
			usort($output, function($a, $b) {
				return strnatcasecmp($a, $b);
			});
			return $output;
		}
	}
