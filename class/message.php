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
		protected $_message;
		protected $_created;

		// Constructor
		public function __construct($row){
			foreach ($row as $key => $value){
				switch ($key){
					case "id":
						$this->setId($value);
						break;
					case "text":
						$this->setText($value);
						break;
					case "sender_id":
						$this->setSenderId($value);
						break;
					case "recipient_id":
						$this->setRecipientId($value);
						break;
					case "message_id":
						$this->setMessageId($value);
						break;
					case "created":
						$this->_created = $value;
						break;
				} 
			}

			if($sender = User::getUserById($this->_senderId)){
				$this->setSender($sender);
			}
			if($recipient = User::getUserById($this->_recipientId)){
				$this->setRecipient($recipient);
			}

			if($message = Message::getMessageById($this->_messageId)){
				$this->setMessage($message);
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

		public function getMessage(){
			return $this->_message;
		}
		
		public function setMessage(Message $message){
			$this->_message = $message;
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
				$row = $st->fetch();
				Data::disconnect();
				if($row){
					return new Message($row);
				}
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

	
	print_r(Message::getMessages());

	
?>
