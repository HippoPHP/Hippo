<?php 

	define('T_NONE', 0);
	define('T_OPEN_CURLY_BRACKET', 1000);
	define('T_CLOSE_CURLY_BRACKET', 1001);
	define('T_OPEN_SQUARE_BRACKET', 1002);
	define('T_CLOSE_SQUARE_BRACKET', 1003);
	define('T_OPEN_PARENTHESIS', 1004);
	define('T_CLOSE_PARENTHESIS', 1005);
	define('T_COLON', 1006);
	define('T_STRING_CONCAT', 1007);
	define('T_INLINE_THEN', 1008);
	define('T_INLINE_ELSE', 1009);
	define('T_NULL', 1010);
	define('T_FALSE', 1011);
	define('T_TRUE', 1012);
	define('T_SEMICOLON', 1013);
	define('T_EQUAL', 1014);
	define('T_MULTIPLY', 1015);
	define('T_DIVIDE', 1016);
	define('T_PLUS', 1017);
	define('T_MINUS', 1018);
	define('T_MODULUS', 1019);
	define('T_POWER', 1020);
	define('T_BITWISE_AND', 1021);
	define('T_BITWISE_OR', 1022);
	define('T_ARRAY_HINT', 1023);
	define('T_GREATER_THAN', 1024);
	define('T_LESS_THAN', 1025);
	define('T_BOOLEAN_NOT', 1026);
	define('T_SELF', 1027);
	define('T_PARENT', 1028);
	define('T_DOUBLE_QUOTED_STRING', 1029);
	define('T_COMMA', 1030);
	define('T_HEREDOC', 1031);
	define('T_PROTOTYPE', 1032);
	define('T_THIS', 1033);
	define('T_REGULAR_EXPRESSION', 1034);
	define('T_PROPERTY', 1035);
	define('T_LABEL', 1036);
	define('T_OBJECT', 1037);
	define('T_COLOUR', 1038);
	define('T_HASH', 1039);
	define('T_URL', 1040);
	define('T_STYLE', 1041);
	define('T_ASPERAND', 1042);
	define('T_DOLLAR', 1043);
	define('T_TYPEOF', 1044);
	define('T_CLOSURE', 1045);
	define('T_BACKTICK', 1046);
	define('T_START_NOWDOC', 1047);
	define('T_NOWDOC', 1048);
	define('T_END_NOWDOC', 1049);
	define('T_OPEN_SHORT_ARRAY', 1050);
	define('T_CLOSE_SHORT_ARRAY', 1051);
	define('T_GOTO_LABEL', 1052);

	if (!defined('T_NAMESPACE')) {
		define('T_NAMESPACE', 1053);
	}

	if (!defined('T_NS_SEPARATOR')) {
		define('T_NS_SEPARATOR', 1054);
	}

	if (!defined('T_GOTO')) {
		define('T_GOTO', 1055);
	}

	if (!defined('T_TRAIT')) {
		define('T_TRAIT', 1056);
	}

	if (!defined('T_INSTEADOF')) {
		define('T_INSTEADOF', 1057);
	}

	if (!defined('T_CALLABLE')) {
		define('T_CALLABLE', 1058);
	}

	if (!defined('T_FINALLY')) {
		define('T_FINALLY', 1059);
	}