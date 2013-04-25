<?php
	require_once("data.php");

	class Country{

		//Atributtes
		protected $_id;
		protected $_name;
		protected $_description;
		protected $_created;
		
		// Constructor
		public function __construct($row){
			foreach ($row as $key => $value){
				switch ($key){
					case "id":
						$this->_id = $value;
						$this->setId($this->_id);
						break;
					case "name":
						$this->_name = $value;
						$this->setName($this->_name);
						break;
					case "description":
						$this->_description = $value;
						$this->setDescription($this->_description);
						break;
					case "created":
						$this->_created = $value;
						break;
				}
			}

		}
		

		//Set & Get Methods
		public function getId(){
			return $this->_id;
		}
		
		public function setId($id){
			$this->_id = $id;
		}		
		
		public function getName(){
			return $this->_name;
		}
		
		public function setName($name){
			$this->_name = $name;
		}
		
		public function getDescription(){
			return $this->_description;
		}
		
		public function setDescription($description){
			$this->_description = $description;
		}
	
		// Methods 

		public function update ($name, $description) {

			$conn = Data::connect();

			$sql = "UPDATE countries SET 
				name = :name,
				description = :description
			WHERE id = :id";

			try {
				$st = $conn->prepare($sql);
				$st->bindValue(":id", $this->_id, PDO::PARAM_INT);
				$st->bindValue(":name", $name, PDO::PARAM_STR);
				$st->bindValue(":description", $description, PDO::PARAM_STR);
				$st->execute();
				Data::disconnect();
			} catch (PDOException $e) {
				Data::disconnect();
				die ("Query failed" . $e->getMessage() );
			}
		}

		public static function getCountries(){
			$conn = Data::connect();
			$sql = "SELECT * FROM countries";
			try{
				$st= $conn->prepare($sql);
				$st->execute();
				$countries = array();
				foreach ($st->fetchAll() as $row) {
					$countries[] = new Country ($row);
				}
				Data::disconnect();
				return $countries;
			} catch (PDOException $e) {
				Data::disconnect();
				die("Query failed: " . $e->getMessage());
			}
		}

		public static function getCountryById($id){
			$conn = Data::connect();
			$sql = "SELECT * FROM countries WHERE id = :id";
			try{
				$st=$conn->prepare($sql);
				$st->bindValue(":id", $id, PDO::PARAM_INT);
				$st->execute();
				$row = $st->fetch();
				Data::disconnect();
				if ($row){
					return new Country($row);
				}
			} catch (PDOException $e) {
				Data::disconnect();
				die("Query failed: " . $e->getMessage());
			}
		}
	}

	/* TEST ONLY
	//Update a country

	$country = Country::getCountryById(1);
	$country->update("venezuela", "Es un país genial");
	
	//Show countries

	print_r(Country::getCountries());
	
	*/

	
?>