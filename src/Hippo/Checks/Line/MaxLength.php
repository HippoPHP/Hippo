<?php

	namespace Hippo\Checks\Line;

	use Hippo\Checks\AbstractCheck;
	use Hippo\Checks\CheckInterface;
	use Hippo\Config\Config;
	use Hippo\File;
	use Hippo\Violation;

	class MaxLength extends AbstractCheck implements CheckInterface {
		/**
		 * Limits for emitting violations.
		 * @var integer[]
		 */
		protected $limits = [
			Violation::SEVERITY_ERROR => 80,
			Violation::SEVERITY_WARNING => null,
			Violation::SEVERITY_INFO => null,
		];

		/**
		 * Defines how many spaces a tab takes up.
		 * @var integer
		 */
		protected $tabExpand = 4;

		/**
		 * Sets the error line length limit.
		 * @param int $violationLevel
		 * @param int $length
		 * @return MaxLength
		 */
		public function setLimit($violationSeverity, $length) {
			$length = ((int) $length);
			$this->limits[$violationSeverity] = $length > 0 ? $length : null;
			return $this;
		}

		/**
		 * Sets how many spaces make up a tab.
		 * @param int $size
		 * @return MaxLength
		 */
		public function setTabExpand($size) {
			$this->tabExpand = (int) $size;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getConfigRoot() {
			return 'file.max_line_length';
		}

		/**
		 * checkFileInternal(): defined by AbstractCheck.
		 * @see AbstractCheck::checkFileInternal()
		 * @param File $file
		 * @param Config $config
		 * @return void
		 */
		protected function checkFileInternal(File $file, Config $config) {
			$this->setLimit(Violation::SEVERITY_ERROR, $config->get('error_limit', $this->errorLimit));
			$this->setLimit(Violation::SEVERITY_WARNING, $config->get('warning_limit', $this->warningLimit));
			$this->setLimit(Violation::SEVERITY_INFO, $config->get('info_limit', $this->infoLimit));
			$this->setTabExpand($config->get('tab_expand', $this->tabExpand));

			foreach ($file->getLines() as $line => $data) {
				$lineLength = iconv_strlen(
					str_replace("\t", str_repeat(' ', $this->tabExpand), rtrim($data, "\r\n")),
					$file->getEncoding()
				);

				$violationLimit = null;
				$severity = null;

				foreach (Violation::getSeverities() as $severity) {
					if (!isset($this->limits[$severity]) || $this->limits[$severity] === null) {
						continue;
					}

					if ($lineLength <= $this->limits[$severity]) {
						continue;
					}

					$this->addViolation(
						$file,
						$line,
						0,
						sprintf('Line is longer than %d characters.', $this->limits[$severity]),
						$severity
					);
				}
			}
		}

	}
