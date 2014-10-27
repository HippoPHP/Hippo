<?php 

	namespace PHPCheckstyle\Check;

	use PHPCheckstyle\File;
	use PHPCheckstyle\Violation;

	abstract class AbstractCheck {
		protected $severity = Violation::SEVERITY_ERROR;

		public function setSeverity($severity) {
			if (NULL !== ($severity = Violation::getSeverityFromString($severity))) {
				$this->severity = $severity;
			}

			return $this;
		}

		protected function addViolation(File $file, $line, $column, $message, $severity = NULL) {
			$source = get_class($this);

			if (strpos($source, 'PHPCheckstyle\\Check\\') === 0) {
				$source = 'PHPCheckstyle\\' . substr($source, strlen('PHPCheckstyle\\Check\\'));
			}

			if ($severity === NULL) {
				$severity = $this->severity;
			}

			$file->addViolation(new Violation($line, $column, $severity, $message, $source));
		}
	}