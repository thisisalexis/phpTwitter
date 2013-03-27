<?php
	class Country{
		//Atributtes
		protected $_id;
		protected $_name;
		protected $_description;
		protected $_created;
		
		//Methods
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
			$this->name = $name;
		}
		
		public function getDescription(){
			return $this->_description;
		}
		
		public function setDescription($description){
			$this->description = $description;
		}
	
	}
?>