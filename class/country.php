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
						$this->setId($value);
						break;
					case "name":
						$this->setName($value);
						break;
					case "description":
						$this->setDescription($value);
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

		public static function create($name, $description){

			$row = array ();

			$row["name"] = $name;
			$row["description"] = $description;
	
		
			$country = New Country ($row); 
			

			$country->save();
		}

		public function save(){
			$conn = Data::connect();
			$sql = "INSERT INTO countries (
				name,
				description
				) VALUES (
				:name,
				:description
				)";

			try{
				$st = $conn->prepare($sql);
				$st->bindValue(":name", $this->_name, PDO::PARAM_STR);
				$st->bindValue(":description", $this->_description, PDO::PARAM_STR);
				$st->execute();
				Data::disconnect();
			} catch (PDOException $e){
				Data::disconnect();
				die ("Query failed " .$e->getMessage());
			}
		}
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

		public static function delete($id){
			$conn = Data::connect();
			$sql = "DELETE FROM countries WHERE id = :id";
			try{
				$st=$conn->prepare($sql);
				$st->bindValue(":id", $id, PDO::PARAM_INT);
				$st->execute();
				Data::disconnect();
			} catch (PDOException $e) {
				Data::disconnect();
				die("Query failed: " . $e->getMessage());
			}
		}


	}

	/* TEST ONLY
	
	//Update country
	$country = Country::getCountryById(1);
	$country->update("venezuela", "Es un país genial");
	
	// Create Country 
	Country::create("México", "prueba");

	//Show countries
	print_r(Country::getCountries());
	
	// Delete Country
	Country::delete(3);

	*/

?>