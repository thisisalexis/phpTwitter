<?php
	require_once("data.php");
	require_once("country.php");
	require_once("follow.php");

	class User{
		//Atributtes
		protected $_id;
		protected $_username;
		protected $_email;
		protected $_firstname;
		protected $_lastname;
		protected $_password;
		protected $_hashedPassword;
		protected $_idCountry;
		protected $_country;
		protected $_bio;
		protected $_birthdate;
		protected $_created;
		
		// Constructor
		public function __construct($row){
			foreach ($row as $key => $value){
				switch ($key){
					case "id":
						$this->setId($value);
						break;
					case "username":
						$this->setUsername($value);
						break;
					case "firstname":
						$this->setFirstname($value);
						break;
					case "lastname":
						$this->setLastname($value);
						break;
					case "email":
						$this->setEmail($value);
						break;
					case "password":
						$this->setPassword($value);
						break;
					case "hashed_password":
						$this->setHashedPassword($value);
						break;
					case "country_id":
						$this->setIdCountry($value);
						$country = Country::getCountryById($value);
						$this->setCountry($country);
						break;
					case "bio":
						$this->setBio($value);
						break;
					case "bithdate":
						$this->setBirthdate($value);
						break;
					case "created":
						$this->_created = $value;
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
		public function getIdCountry(){
			return $this->_idCountry;
		}
		
		public function setIdCountry($idcountry){
			$this->_idCountry = $idcountry;
		}

		public function getCountry(){
			return $this->_country;
		}
		
		public function setCountry( Country $country){
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


		public static function login($username, $password){
			
			$conn = Data::connect();
			$sql = "SELECT * FROM users WHERE username = :username AND password = password(:password)";

			try{
				$st = $conn->prepare($sql);
				$st->bindValue(":username", $username, PDO::PARAM_STR);
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


		public function update ($username, $firstname, $lastname, $email, $password, $country, $bio, $birthdate) {

			$conn = Data::connect();
			$passwordSql = $password ? "password = password(:password)," : "";

			$sql = "UPDATE users SET 
				username = :username,
				firstname = :firstname,
				lastname = :lastname,
				email = :email,
				$passwordSql
				country_id = :country_id,
				bio = :bio,
				bithdate = :bithdate
			WHERE id = :id";

			try {
				$st = $conn->prepare($sql);
				$st->bindValue(":id", $this->_id, PDO::PARAM_INT);
				$st->bindValue(":username", $username, PDO::PARAM_STR);
				$st->bindValue(":firstname", $firstname, PDO::PARAM_STR);
				$st->bindValue(":lastname", $lastname, PDO::PARAM_STR);
				$st->bindValue(":email", $email, PDO::PARAM_STR);
				if ($this->_password =! ""){
					$st->bindValue(":password", $password, PDO::PARAM_STR);
				}
				$st->bindValue(":country_id", $country, PDO::PARAM_INT);
				$st->bindValue(":bio", $bio, PDO::PARAM_STR);
				$st->bindValue(":bithdate", $birthdate, PDO::PARAM_STR);
				$st->execute();
				Data::disconnect();
			} catch (PDOException $e) {
				Data::disconnect();
				die ("Query failed" . $e->getMessage() );
			}
		}

		public static function getUserById($id){
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

		public function block($followingId, $followerId){
			Follow::delete($followingId, $followerId);
		}

		public function unfollow($followingId, $followerId){
			Follow::delete($followingId, $followerId);
		}
	}

	
	/* TEST ONLY
	
	// signUp
	User::signUp("giovi18", "Giovanna", "Russo", "russopgiovanna@gmail.com", "123456", 1, "Probando.", ""); 
	
	// Show user
	echo print_r(User::getUsers());
	
	
	// testing update method
	$user = User::getUserById(2);

	$user->update("juanito", "pupu", "Perez", "pperez@gmail.com", "123456", 2, "nothing but the beat", "fg");

	*/
	
	
?>