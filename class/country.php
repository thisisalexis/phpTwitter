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
	}

	echo print_r(Country::getCountries());

?>