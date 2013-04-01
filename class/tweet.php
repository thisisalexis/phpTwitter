<?php
	class Tweet{
		//ORM Attributes
		private $_id;
		private $_user;
		private $_text;
		private $_created;
		private $_tweet;

		//Other attributes
		private $_limit = 20; //Default tweets per page

		//Constructor
		__construct(User $user, $text, Tweet $tweet = null){

		}

		//Set and Get methods
		public function getId(){
			return $this->_id;
		}

		public function getUser(){
			return $this->_user;
		}

		public function setUser($user){
			$this->_user = $user;
		}

		public function getText(){
			return $this->_text;
		}

		public function setText($text){
			$this->_text = $text;
		}

		public function getCreated(){
			return $this->_created;
		}

		public function getTweet(){
			return $this->_tweet;
		}

		public function setTweet($tweet){
			$this->_tweet = $tweet;
		}

		//Public class methods
		public function delete(){
			$result = false;
			return $result;
		}

		//static methods
		public static function getTweetById($id){
			tweet = new Tweet;

			return tweet;
		}

		public static function getTweetsByUserId($userId, $from = 0, $limit = $this->_limit){
			tweets = new Tweet[];
			return tweets;
		}

		public static function getTimeLineByUser($UserId, $from = 0, limit = $this->_limit){
			tweets = new Tweet[];
			return tweets;
		}

	}
?>