<?php
	require_once("data.php");
	require_once("user.php");

	class Message{

		//Atributtes
		protected $_id;
		protected $_text;
		protected $_senderId;
		protected $_recipientId;
		protected $_sender;
		protected $_recipient;
		protected $_messageId;
		protected $_created;

		// Constructor
		public function __construct($row){
			foreach ($row as $key => $value){
				switch ($key){
					case "id":
						$this->_id = $value;
						$this->setId($this->_id);
						break;
					case "text":
						$this->_text = $value;
						$this->setText($this->_text);
						break;
					case "sender_id":
						$this->_senderId = $value;
						$this->setSenderId($this->_senderId);
						break;
					case "recipient_id":
						$this->_recipientId = $value;
						$this->setRecipientId($this->_recipientId);
						break;
					case "message_id":
						$this->_messageId = $value;
						$this->setMessageId($this->_messageId);
						break;
					case "created":
						$this->_created = $value;
						break;
				}
				 
			}

			if($sender = User::getUserById($this->_senderId)){
				$this->_sender = $sender;
			}
			if($recipient = User::getUserById($this->_recipientId)){
				$this->_recipient = $recipient;
			}

		}
	
		//Set & Get Methods
		public function getId(){
			return $this->_id;
		}
		
		public function setId($id){
			$this->_id = $id;
		}		
		
		public function getText(){
			return $this->_text;
		}
		
		public function setText($text){
			$this->_text = $text;
		}
		
		public function getSenderId(){
			return $this->_senderId;
		}
		
		public function setSenderId($senderId){
			$this->_senderId = $senderId;
		}

		public function getRecipientId(){
			return $this->_recipientId;
		}
		
		public function setRecipientId($recipientId){
			$this->_recipientId = $recipientId;
		}		
		
		public function getSender(){
			return $this->_sender;
		}
		
		public function setSender(User $sender){
			$this->_sender = $sender;
		}
		
		public function getRecipient(){
			return $this->_recipient;
		}
		
		public function setRecipient(User $recipient){
			$this->_recipient = $recipient;
		}

		public function getMessageId(){
			return $this->_messageId;
		}
		
		public function setMessageId($messageId){
			$this->_messageId = $messageId;
		}

		public function getCreated(){
			return $this->_created;
		}

		//Methods


		public static function create($text, $senderId, $recipientId, $messageId = null){

			$row = array ();

			$row["text"] = $text;
			$row["sender_id"] = $senderId;
			$row["recipient_id"] = $recipientId;
			$row["message_id"] = $messageId;
	
		
			$message = New Message ($row); 
			

			$message->save();
		}

		public function save(){
			$conn = Data::connect();
			$sql = "INSERT INTO messages (
				text,
				sender_id,
				recipient_id,
				message_id
				) VALUES (
				:text,
				:sender,
				:recipient,
				:message
				)";

			try{
				$st = $conn->prepare($sql);
				$st->bindValue(":text", $this->_text, PDO::PARAM_STR);
				$st->bindValue(":sender", $this->_senderId, PDO::PARAM_INT);
				$st->bindValue(":recipient", $this->_recipientId, PDO::PARAM_INT);
				$st->bindValue(":message", $this->_messageId, PDO::PARAM_INT);
				$st->execute();
				Data::disconnect();
			} catch (PDOException $e){
				Data::disconnect();
				die ("Query failed " .$e->getMessage());
			}
		}

		public static function getMessageById($id){
			$conn = Data::connect();
			$sql = "SELECT * FROM messages WHERE id = :id";

			try{
				$st = $conn->prepare($sql);
				$st->bindValue(":id", $id, PDO::PARAM_INT);
				$st->execute();
				$message = array ();
				foreach ($st->fetchAll as $row){
					$message[]= New Message ($row);
				}
				Data::disconnect();
				return $message;
			} catch (PDOException $e){
				Data::disconnect();
				die ("Query Failed" . $e->getMessage());
			}
		}

		public static function getMessageBySender($senderId){
			$conn = Data::connect();
			$sql = "SELECT * FROM messages WHERE sender_id = :id";

			try{
				$st = $conn->prepare($sql);
				$st->bindValue(":id", $senderId, PDO::PARAM_INT);
				$st->execute();
				$messages = array();
				foreach ($st->fetchAll() as $row) {
					$messages[] = new Message ($row);
				}
				Data::disconnect();
				return $messages;
			} catch (PDOException $e){
				Data::disconnect();
				die ("Query Failed" . $e->getMessage());
			}
		}

		public static function getMessageByRecipient($recipientId){
			$conn = Data::connect();
			$sql = "SELECT * FROM messages WHERE recipient_id = :id";

			try{
				$st = $conn->prepare($sql);
				$st->bindValue(":id", $recipientId, PDO::PARAM_INT);
				$st->execute();
				$messages = array();
				foreach ($st->fetchAll() as $row) {
					$messages[] = new Message ($row);
				}
				Data::disconnect();
				return $messages;
			} catch (PDOException $e){
				Data::disconnect();
				die ("Query Failed" . $e->getMessage());
			}
		}


		public static function getMessages(){
			$conn = Data::connect();
			$sql = "SELECT * FROM messages";
			try{
				$st= $conn->prepare($sql);
				$st->execute();
				$messages = array();
				foreach ($st->fetchAll() as $row) {
					$messages[] = new Message ($row);
				}
				Data::disconnect();
				return $messages;
			} catch (PDOException $e) {
				Data::disconnect();
				die("Query failed: " . $e->getMessage());
			}
		}


		public static function delete($id){
			$conn = Data::connect();
			$sql = "DELETE FROM messages WHERE id = :id";
			try{
				$st = $conn->prepare($sql);
				$st->bindValue(":id", $id, PDO::PARAM_INT);
				$st->execute();
				Data::disconnect();
			} catch (PDOException $e){
				Data::disconnect();
				die("Query failed: " .$e->getMessage());
			}
		}
	}

	/* TEST ONLY
	
	//Show Messages
	print_r(Message::getMessages());

	//Create Message
	Message::create("hi", 2, 3, 2);
	Message::create("hi there", 2, 3);

	//Delete Message
	Message::delete(9);

	//Show Messages by recipient
	print_r(Message::getMessageByRecipient(3));

	//Show Messages by sender
	print_r(Message::getMessageBySender(3));

	*/

	
	

	
?>
