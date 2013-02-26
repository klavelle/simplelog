<?php
/**
 * SimpleLog
 *
 * A Simple log class that essentially wraps php logging functionality
 *
 */

class SimpleLog
{
	private static $instance;

	public function __construct() { }

	/**
	 * returns the singleton SimpleLog instance
	 *
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			$classname = get_class();
			self::$instance = new $classname;
		}

		return self::$instance;
	}

	/**
	 * Takes any number of arguments and print_r() them.
	 */
	private static function writeLog()
	{
		$arguments = func_get_args();
		$errorMessage = '';

		foreach($arguments as $argument)
		{
			$errorMessage .= print_r($argument, TRUE) . "\n";
		}

		error_log($errorMessage);
	}

	/**
	 * A shortcut function for simple error logging that adds basic backtrace
	 * info to the log message
	 */
	public static function log()
	{
		self::getInstance();

		$args = func_get_args();
		$backtrace = debug_backtrace(FALSE);

		if($backtrace[0]['function'] == 'log')
		{
			array_unshift($args,'Logger from: ' . $backtrace[0]['file'] . " on line " . $backtrace[0]['line'] . "\n\n");
		}

		call_user_func_array('self::writeLog', $args);
	}

	/**
	 * A shortcut to send just the backtrace info to the log file
	 */
	public static function backtrace()
	{
		self::getInstance();
		self::log(debug_backtrace());
	}

	/**
	 * Attempts to retrieve the current host from PHP vars
	 *
	 */
	public static function getHost()
	{
		$http_host = $_SERVER['HTTP_HOST']; 
		$server_name = $_SERVER['SERVER_NAME'];

		if (empty($server_name))
		{
			$url = explode(":", $http_host, 1);
			$host = $url[0];
		}
		else
		{
			$host = $server_name;
		}

		return htmlentities($host);
	}

}

?>

