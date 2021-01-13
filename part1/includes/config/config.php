<?php

/**
 * Exception Handler function that responds with appropritate status code and errors to a log file and a specific message to users.
 */
function exceptionHandler($e)
{
	$msg = "status: 500 - Message: " . $e->getMessage() . " - File: " . $e->getFile() . " - Line: " . $e->getLine() . "";

	$user_msg = array("status" => "500", "message" => "OOPS! That wasn't supposed to happen.");
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: GET, POST");
	echo json_encode($user_msg);
	logError($msg);
}
set_exception_handler('exceptionHandler');

/**
 * Error Handler function that responds with appropriate message to the users and logs the error into a file.
 */
function errorHandler($errno, $errstr, $errfile, $errline)
{
	if ($errno != 2 && $errno != 8) {
		throw new Exception("OOPS!, That wasn't supposed to happen.", 1);
	} else {
		logError("[$errno] $errstr line: $errline - file: $errfile");
	}
}
set_error_handler('errorHandler');

/**
 * Log error function that displays the information into a error log file.
 * @param $msg - called in other functions with specific message.
 */
function logError($msg)
{
	$logError = fopen("logs/errorLogs.txt", "w");
	$content = $msg;
	fwrite($logError, $content);
	fclose($logError);
}

/**
 * Auto load classes in the classes directory.
 */
function autoloadClasses($className)
{
	$filename = "includes\classes\\" . strtolower($className) . ".class.php";
	$filename = str_replace('\\', DIRECTORY_SEPARATOR, $filename);
	if (is_readable($filename)) {
		include_once $filename;
	} else {
		throw new exception("File not found: " . $className . " (" . $filename . ")");
	}
}
spl_autoload_register("autoloadClasses");

$ini['main'] = parse_ini_file("config.ini", true);

/**
 * Define base paths.
 */
define('BASEPATH', $ini['main']['paths']['basepath']);
define('CSSPATH', $ini['main']['paths']['css']);
define('JWTKEY', $ini['main']['token']['token']);

?>