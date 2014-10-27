<?php 

	namespace PHPCheckstyle\PHPCheckstyle;

	class Token {
		protected $type;
		protected $lexeme;
		protected $line;
		protected $column;
		protected $level;
		protected $namespace;

		public function __construct($type, $lexeme, $line, $column) {
			$this->type = $type;
			$this->lexeme = $lexeme;
			$this->line = $line;
			$this->column = $column;
		}

		public function getType() {
			return $this->type;
		}

		public function getLexeme() {
			return $this->lexeme;
		}

		public function getLine() {
			return $this->line;
		}

		public function getColumn() {
			return $this->column;
		}

		public function setLevel($level) {
			$this->level = $level;
			return $this;
		}

		public function getLevel() {
			return $this->level;
		}

		public function setNamespace($namespace) {
			$this->namespace = $namespace;
			return $this;
		}

		public function getNamespace() {
			return $this->namespace;
		}

		public function hasNewline() {
			if (preg_match('([\r\n])', $this->lexeme)) {
				return TRUE;
			}

			return FALSE;
		}

		public function getNewlineCount() {
			preg_match_all('(\n|\r\n?)', $this->lexeme, $matches, PREG_SET_ORDER);
			return count($matches);
		}

		public function getTrailingLineLength() {
			return iconv_strlen(
				substr(strrchr($this->lexeme, "\n") ?: strrchr($this->lexeme, "\r"), 1),
				'utf-8'
			);
		}

		public function getLength() {
			return iconv_strlen($this->lexeme, 'utf-8');
		}
	}