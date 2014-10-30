<?php
	
	define('T_NEW_LINE', 10000);
	define('T_TAB', 10001);
	define('T_SEMICOLON', 10002); // ;
	define('T_BRACES_OPEN', 10003); // { To avoid confusion with T_CURLY_OPEN
	define('T_BRACES_CLOSE', 10004); // }
	define('T_PARENTHESIS_OPEN', 10005); // (
	define('T_PARENTHESIS_CLOSE', 10006); // )
	define('T_COMMA', 10007); // ,
	define('T_EQUAL', 10008); // =
	define('T_CONCAT', 10009); // .
	define('T_COLON', 10010); // :
	define('T_MINUS', 10011); // -
	define('T_PLUS', 10012); // +
	define('T_IS_GREATER', 10013); // >
	define('T_IS_SMALLER', 10014); // <
	define('T_MULTIPLY', 10015); // *
	define('T_DIVIDE', 10016); // /
	define('T_QUESTION_MARK', 10017); // ?
	define('T_MODULO', 10018); // %
	define('T_EXCLAMATION_MARK', 10019); // !
	define('T_AMPERSAND', 10020); // %
	define('T_SQUARE_BRACKET_OPEN', 10021); // [
	define('T_SQUARE_BRACKET_CLOSE', 10022); // ]
	define('T_AROBAS', 10023); // @
	define('T_QUOTE', 10024); // " (only detected before and after a T_ENCAPSED_AND_WHITESPACE) otherwise should be included in T_CONSTANT_ENCAPSED_STRING
	define('T_UNKNOWN', -1);

	// PHP 5.3 parsing with an older version
	if (!defined('T_FINALLY')) {
		define('T_FINALLY', 10025);
	}
	if (!defined('T_CALLABLE')) {
		define('T_CALLABLE', 10026);
	}
	if (!defined('T_TRAIT')) {
		define('T_TRAIT', 10027);
	}
	if (!defined('T_TRAIT_C')) {
		define('T_TRAIT_C', 10028);
	}
	if (!defined('T_INSTEADOF')) {
		define('T_INSTEADOF', 10029);
	}
	if (!defined('T_YIELD')) {
		define('T_YIELD', 10030);
	}