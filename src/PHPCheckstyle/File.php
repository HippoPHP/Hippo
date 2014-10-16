<?php 

	namespace PHPCheckstyle;

	class PHPCheckstyle_File {
		/**
		 * The absolute file path.
		 * @var string
		 */
		private $file = '';

		/**
		 * The EOL character the file is using.
		 * @var string
		 */
		public $eolChar = '';

		/**
		 * The PHPCheckstyle object controlling this check.
		 * @var PHPCheckstyle
		 */
		public $phpcs = NULL;

		/**
		 * The tokenizer being used for this file.
		 * @var object
		 */
		public $tokenizer = NULL;

		/**
		 * The number of tokens in this file.
		 * @var integer
		 */
		public $numTokens = 0;

		/**
		 * Tokens stack map.
		 * @var array
		 */
		private $_tokens = array();

		/**
		 * Any errors raised by this file.
		 * @var array
		 */
		private $_errors = array();

		/**
		 * Warnings raised by this file
		 * @var array
		 */
		private $_warnings = array();

		/**
		 * Number of errors raised.
		 * @var integer
		 */
		private $_errorCount = 0;

		/**
		 * Number of warnings raised.
		 * @var integer
		 */
		private $_warningCount = 0;
	}