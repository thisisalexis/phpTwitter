<?php
	require_once("user.php");
	require_once("data.php");

	class Follow{

		//Attributes
		protected $_id;
		protected $_followingId;
		protected $_followerId;
		protected $_following;
		protected $_follower;
		protected $_created;

		// Constructor
		public function __construct($row){
			foreach ($row as $key => $value){
				switch ($key){
					case "id":
						$this->setId($value);
						break;
					case "following_id":
						$this->setFollowingId($value);
						$following = User::getUserById($value);
						$this->setFollowing($following);
						break;
					case "follower_id":
						$this->setFollowerId($value);
						$follower = User::getUserById($value);
						$this->setFollower($follower);
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

		public function getFollowingId(){
			return $this->_followingId;
		}

		public function setFollowingId($followingId){
			$this->_followingId = $followingId;
		}

		public function getFollowerId(){
			return $this->_followerId;
		}

		public function setFollowerId($followerId){
			$this->_followerId = $followerId;
		}

		public function getFollowing(){
			return $this->_following;
		}

		public function setFollowing(User $following){
			$this->_following = $following;
		}

		public function getFollower(){
			return $this->_follower;
		}

		public function setFollower(User $follower){
			$this->_follower = $follower;
		}

		public function getCreated(){
			return $this->_created;
		}

		//Methods

		public static function create($followerId, $followingId){
			$conn = Data::connect();
			$sql = "INSERT INTO follows (
				follower_id,
				following_id
				) VALUES (
				:follower,
				:following
				)";

			try{
				$st = $conn->prepare($sql);
				$st->bindValue(":follower", $followerId, PDO::PARAM_INT);
				$st->bindValue(":following", $followingId, PDO::PARAM_INT);
				$st->execute();
				Data::disconnect();
			} catch (PDOException $e){
				Data::disconnect();
				die ("Query failed " .$e->getMessage());
			}
		}


		public static function getFollows(){
			$conn = Data::connect();
			$sql = "SELECT * FROM follows";

			try{
				$st= $conn->prepare($sql);
				$st->execute();
				$follows = array();
				foreach ($st->fetchAll() as $row){
					$follows[] = new Follow ($row);
				}
				Data::disconnect();
				return $follows;
			} catch (PDOException $e){
				Data::disconnect();
				die("Query failed" . $e->getMessage());
			}

		}

		public static function getFollowerById($id){
			$conn = Data::connect();
			$sql = "SELECT * FROM follows WHERE follower_id = :id";

			try{
				$st = $conn->prepare($sql);
				$st->bindValue(":id", $id, PDO::PARAM_INT);
				$st->execute();
				$followers = array ();
				foreach ($st->fetchAll() as $row){
					$followers[] = new Follow($row);
				}
				Data::disconnect();
				return $followers;
			} catch (PDOException $e){
				Data::disconnect();
				die("Query failed:" . $e->getMessage());
			}
		}

		public static function getFollowingById($id){
			$conn = Data::connect();
			$sql = "SELECT * FROM follows WHERE following_id = :id";

			try{
				$st = $conn->prepare($sql);
				$st->bindValue(":id", $id, PDO::PARAM_INT);
				$st->execute();
				$followings = array ();
				foreach ($st->fetchAll() as $row){
					$followings[] = new Follow($row);
				}
				Data::disconnect();
				return $followings;
			} catch (PDOException $e){
				Data::disconnect();
				die("Query failed:" . $e->getMessage());			
			}
		}

		public static function delete($followingId, $followerId){
			$conn = Data::connect();
			$sql = "DELETE FROM follows WHERE following_id = :followingId
										AND follower_id = :followerId";
			try{
				$st = $conn->prepare($sql);
				$st->bindValue(":followingId", $followingId, PDO::PARAM_INT);
				$st->bindValue(":followerId", $followerId, PDO::PARAM_INT);
				$st->execute();
				Data::disconnect();
			} catch (PDOException $e){
				Data::disconnect();
				die("Query failed: " .$e->getMessage());
			}
		}
	
 	}

 	/* TEST ONLY
	print_r(Follow::getFollows());

	Show followers
	print_r(Follow::getFollowerById(3));

	Show followings
	print_r(Follow::getFollowingById(5));

	Delete follow
	Follow::delete(3);

	Delete follow by an user instance 
	$user = User::getUserById(3);
	$user->block(3,2);

	*/
	
?>

