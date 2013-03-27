<?php
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
		
		//Methods
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
	
	}
?>