<?php

require 'mongo-php-driver/vendor/autoload.php'; // include Composer's autoloader


// klasa za povezivanje na bazu
class DB
{
	private static $db = null;

	private function __construct() { }
	private function __clone() { }

	public static function getConnection()
	{
		if( DB::$db === null )
		{
			try
			{
				//otvaramo bazu
				$mongo = new MongoDB\Client("mongodb://localhost:27017");

				DB::$db = $mongo->nmbp; //returns a nmbp database

			}
			catch( PDOException $e ) { exit( 'PDO Error: ' . $e->getMessage() ); }
		}
		return DB::$db;
	}
}

?>
