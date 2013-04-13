<?php
	class Data{

		//Methods

		public static function connect(){
			$dns = "mysql:host=localhost;dbname=twitter";
			$username = "root";
			$password = "1234";
			try{
				$conn = new PDO ($dns, $username, $password);
				$conn->setAttribute (PDO::ATTR_PERSISTENT, true);
				$conn->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch (PDOException $e){
				die ("Connection failed: ".$e->getMessage());
			}
			return $conn;
		}

		public static function disconnect(){
			$conn = "";
		}

	}

?>