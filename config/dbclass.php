<?php
class DBClass {

	private $host = "127.0.0.1";
	private $username = "root";
	private $password = "tuka1994";
	private $database = "vendasRelatorio";

	public $connection;

	// get the database connection
	public function getConnection(){

		$this->connection = null;

		try{

			$this->connection = new PDO("mysql:host=" . $this->host . ";port=3306;", $this->username, $this->password);
			$this->connection->exec("CREATE DATABASE IF NOT EXISTS " . $this->database );
			$this->connection = new PDO("mysql:host=" . $this->host . ";port=3306;dbname=" . $this->database, $this->username, $this->password);
			$this->connection->exec("set names utf8");

		}catch(PDOException $exception){
			echo "Error Connection: " . $exception->getMessage();
		}

		return $this->connection;
	}
}