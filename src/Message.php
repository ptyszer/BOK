<?php

class Message
{
    private $id;
    private $conversationId;
    private $senderId;
    private $text;
    private $creationDate;


    public function __construct()
    {
        $this->id = -1;
        $this->conversationId = 0;
        $this->senderId = 0;
        $this->text = '';
        $this->creationDate = '';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getConversationId(): int
    {
        return $this->conversationId;
    }

    public function setConversationId(int $conversationId)
    {
        $this->conversationId = $conversationId;
    }

    public function getSenderId(): int
    {
        return $this->senderId;
    }

    public function setSenderId(int $senderId)
    {
        $this->senderId = $senderId;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }

    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    public function setCreationDate(string $creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function saveToDB(PDO $conn)
    {
        if ($this->id == -1) {

            $sql = 'INSERT INTO messages(conversationId, senderId, text, creationDate) VALUES(:conversationId, :senderId, :text, :creationDate)';
            $stmt = $conn->prepare($sql);
            $result = $stmt->execute(['conversationId' => $this->conversationId,
                'senderId' => $this->senderId,
                'text' => $this->text,
                'creationDate' => $this->creationDate]);
            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        }
        return false;
    }

    static public function loadAllMessages(PDO $conn, $conversationId){
        $ret = [];
        $stmt = $conn->prepare('SELECT * FROM messages WHERE conversationId=:conversationId ORDER BY creationDate DESC');
        $result = $stmt->execute(['conversationId' => $conversationId]);
        if ($result !== false && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->conversationId = $row['conversationId'];
                $loadedMessage->senderId = $row['senderId'];
                $loadedMessage->text = $row['text'];
                $loadedMessage->creationDate = $row['creationDate'];
                $ret[] = $loadedMessage;
            }
            return $ret;
        }
        return null;
    }
}