<?php
	require_once("data.php");
	class User{
		//Atributtes
		protected $_id;
		protected $_username;
		protected $_email;
		protected $_firstname;
		protected $_lastname;
		protected $_password;
		protected $_hashedPassword;
		protected $_country;
		protected $_bio;
		protected $_birthdate;
		protected $_created;
		
		// Constructor
		public function __construct($row){
			foreach ($row as $key => $value){
				switch ($key){
					case "id":
						$this->_data["id"] = $value;
						break;
					case "username":
						$this->_data["username"] = $value;
						break;
					case "firstname":
						$this->_data["firstname"] = $value;
						break;
					case "lastname":
						$this->_data["lastname"] = $value;
						break;
					case "email":
						$this->_data["email"] = $value;
						break;
					case "password":
						$this->_data["password"] = $value;
						break;
					case "hashed_password":
						$this->_data["hashed_password"] = $value;
						break;
					case "country_id":
						$this->_data["country_id"] = $value;
						break;
					case "bio":
						$this->_data["bio"] = $value;
						break;
					case "bithdate":
						$this->_data["bithdate"] = $value;
						break;
					case "created":
						$this->_data["created"] = $value;
						break;
				}
			}
			$this->_id = $this->_data["id"];
			$this->_username = $this->_data["username"];
			$this->_firstname = $this->_data["firstname"];
			$this->_lastname = $this->_data["lastname"];
			$this->_email = $this->_data["email"];
			$this->_password = $this->_data["password"];
			$this->_hashedPassword = $this->_data["hashed_password"];
			$this->_country = $this->_data["country_id"];
			$this->_bio = $this->_data["bio"];
			$this->_birthdate= $this->_data["bithdate"];
			$this->_created = $this->_data["created"];
		}
		
		// Set & Get Methods
		public function getId(){
			return $this->_id;
		}
		
		public function setId($id){
			$this->_id = $id;
		}		
		
		public function getUsername(){
			return $this->_username;
		}
		
		public function setUsername($username){
			$this->_username = $username;
		}
		
		public function getFirstname(){
			return $this->_firstname;
		}
		
		public function setFirstname($firstname){
			$this->_firstname = $firstname;
		}
				
		public function getLastname(){
			return $this->_lastname;
		}
		
		public function setLastname($lastname){
			$this->lastname = $lastname;
		}
		
		public function getEmail(){
			return $this->_email;
		}
		
		public function setEmail($email){
			$this->_email = $email;
		}
		
		public function getPassword(){
			return $this->_password;
		}
		
		public function setPassword($password){
			$this->_password = $password;
		}
		
		public function getHashedPassword(){
			return $this->_hashedPassword;
		}
		
		public function setHashedPassword($hashedPassword){
			$this->_hashedPassword = hashedPassword;
		}
		public function getCountry(){
			return $this->_country;
		}
		
		public function setCountry($country){
			$this->_country = $country;
		}
		
		public function getBio(){
			return $this->_bio;
		}
		
		public function setBio($bio){
			$this->_bio = $bio;
		}
		
		public function getBirthdate(){
			return $this->_birthdate;
		}
		
		public function setBirthdate($birthdate){
			$this->_birthdate = $birthdate;
		}
		
		public function getCreated(){
			return $this->_created;
		}
		
		// Methods
		public static function singUp($username, $firstname, $lastname, $email, $password, $country,$bio, $birthdate){
		}

		public static function login ($username, $password){
		}

		public function logout(){
		}

		public function updateInfo(){
		}

		public function getUserById($id){
			$conn = Data::connect();
			$sql = "SELECT * FROM users WHERE id = :id";
			try{
				$st=$conn->prepares($sql);
				$st->execute();
				$row = $st->fetch();
				Data::disconnect();
				if ($row){
					return new User($row);
				}
			} catch (PDOException $e) {
				Data::disconnect();
				die("Query failed: " . $e->Message());
			}
		}

		public static function getUsers (){
			$conn = Data::connect();
			$sql = "SELECT * FROM users";
			try{
				$st= $conn->prepare($sql);
				$st->execute();
				$users = array();
				foreach ($st->fetchAll() as $row) {
					$users[] = new User ($row);
				}
				Data::disconnect();
				return $users;
			} catch (PDOException $e) {
				Data::disconnect();
				die("Query failed: " . $e->Message());
			}
		}

		public static function getUsersbyUsername (){
		}
	
	}
?>