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
						$this->setId($this->_data["id"]);
						break;
					case "username":
						$this->_data["username"] = $value;
						$this->setUsername($this->_data["username"]);
						break;
					case "firstname":
						$this->_data["firstname"] = $value;
						$this->setFirstname($this->_data["firstname"]);
						break;
					case "lastname":
						$this->_data["lastname"] = $value;
						$this->setLastname($this->_data["lastname"]);
						break;
					case "email":
						$this->_data["email"] = $value;
						$this->setEmail($this->_data["email"]);
						break;
					case "password":
						$this->_data["password"] = $value;
						$this->setPassword($this->_data["password"]);
						break;
					case "hashed_password":
						$this->_data["hashed_password"] = $value;
						$this->setHashedPassword($this->_data["hashed_password"]);
						break;
					case "country_id":
						$this->_data["country_id"] = $value;
						$this->setCountry($this->_data["country_id"]);
						break;
					case "bio":
						$this->_data["bio"] = $value;
						$this->setBio($this->_data["bio"]);
						break;
					case "bithdate":
						$this->_data["bithdate"] = $value;
						$this->setBirthdate($this->_data["bithdate"]);
						break;
					case "created":
						$this->_data["created"] = $value;
						$this->_created = $this->_data["created"];
						break;
				}
			}

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

			/* testing a set method
			$this->_firstname = strtoupper($firstname); */
		}
				
		public function getLastname(){
			return $this->_lastname;
		}
		
		public function setLastname($lastname){
			$this->_lastname = $lastname;
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
			$this->_hashedPassword = $hashedPassword;
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

			$row = array ();

			$row["username"] = $username;
			$row["firstname"] = $firstname;
			$row["lastname"] = $lastname;
			$row["email"] = $email;
			$row["password"] = $password;
			$row["hashed_password"] = $password;
			$row["country_id"] = $country;
			$row["bio"] = $bio;
			$row["bithdate"] = $birthdate;
		
			$user = New User ($row); 
			

			$user->save();
		}

		public function save(){
			$conn = Data::connect();
			$sql = "INSERT INTO users (
				username,
				firstname,
				lastname,
				email,
				password,
				hashed_password,
				country_id,
				bio, 
				bithdate
				) VALUES (
				:username,
				:firstname,
				:lastname,
				:email,
				:password,
				:hashedPassword,
				:country,
				:bio,
				:bithdate
				)";

			try{
				$st = $conn->prepare($sql);
				$st->bindValue(":username", $this->_username, PDO::PARAM_STR);
				$st->bindValue(":firstname", $this->_firstname, PDO::PARAM_STR);
				$st->bindValue(":lastname", $this->_lastname, PDO::PARAM_STR);
				$st->bindValue(":email", $this->_email, PDO::PARAM_STR);
				$st->bindValue(":password", $this->_password, PDO::PARAM_STR);
				$st->bindValue(":hashedPassword", $this->_hashedPassword, PDO::PARAM_STR);
				$st->bindValue(":country", $this->_country, PDO::PARAM_INT);
				$st->bindValue(":bio", $this->_bio, PDO::PARAM_STR);
				$st->bindValue(":bithdate", $this->_birthdate, PDO::PARAM_STR);
				$st->execute();
				Data::disconnect();
			} catch (PDOException $e){
				Data::disconnect();
				die ("Query failed " .$e->getMessage());
			}
		}


		public function login($username, $password){
			$conn = Data::connect();
			$sql = "SELECT * FROM users WHERE username = :username AND password = password(:password)";

			try{
				$st = $conn->prepare($sql);
				$st->bindValue(":username", $username PDO::PARAM_STR);
				$st->bindValue(":password", $password, PDO::PARAM_STR);
				$st->execute();
				$row = $st->fetch();
				Data::disconnect();
				if ($row){
					$user = new User ($row);
					$_SESSION["user"] = $row;
				} else{
					header('"location: login.php?username=' . $username  . '"');
					$username = $_GET['username'];
				}
			} catch (PDOException $e) {
					Data::disconnect();
					die("Query failed " . $e->getMessage());
				}

		} 

		public function logout(){
			$_SESSION ["user"] = "";
			header ("location : login.php");
		}

		public function updateInfo(){
		}

		public function getUserById($id){
			$conn = Data::connect();
			$sql = "SELECT * FROM users WHERE id = :id";
			try{
				$st=$conn->prepare($sql);
				$st->bindValue(":id", $id, PDO::PARAM_INT);
				$st->execute();
				$row = $st->fetch();
				Data::disconnect();
				if ($row){
					return new User($row);
				}
			} catch (PDOException $e) {
				Data::disconnect();
				die("Query failed: " . $e->getMessage());
			}
		}

		public static function getUsersbyUsername ($username){
			$conn= Data::connect();
			$sql= "SELECT * FROM users WHERE username = :username";
			try{
				$st=$conn->prepare($sql);
				$st->bindValue(":username", $username, PDO::PARAM_STR);
				$st->execute();
				$row = $st->fetch();
				Data::disconnect();
				if($row){
					return new User($row);
				}
			} catch(PDOException $e){
				Data::disconnect();
				die("Query failed: " . $e->getMessage());
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
				die("Query failed: " . $e->getMessage());
			}
		}
	}

	
	/* Test only
	
	User::singUp( "pedro77", "Pedro", "Perez", "pperez@gmail.com", "123456", 2, "nothing but the beat", ""); 
	
	echo print_r(User::getUsers()); */
	
?>