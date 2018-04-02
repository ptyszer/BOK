<?php

class Conversation
{
    private $id;
    private $clientId;
    private $supportId;
    private $subject;
    private $creationDate;

    /**
     * Conversation constructor.
     * @param $id
     * @param $clientId
     * @param $supportId
     * @param $subject
     */
    public function __construct()
    {
        $this->id = -1;
        $this->clientId = null;
        $this->supportId = null;
        $this->subject = '';
        $this->creationDate = '';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getClientId()
    {
        return $this->clientId;
    }


    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }


    public function getSupportId()
    {
        return $this->supportId;
    }

    public function setSupportId($supportId)
    {
        $this->supportId = $supportId;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
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

            $sql = 'INSERT INTO conversations(subject, clientId, supportId, creationDate) VALUES(:subject, :clientId, :supportId, :creationDate)';
            $stmt = $conn->prepare($sql);
            $result = $stmt->execute(['subject' => $this->subject,
                'clientId' => $this->clientId,
                'supportId' => $this->supportId,
                'creationDate' => $this->creationDate]);
            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        }
        return false;
    }

    static public function loadAllConversationsByClientId(PDO $conn, $clientId){
        $ret = [];
        $stmt = $conn->prepare('SELECT * FROM conversations WHERE clientId=:clientId ORDER BY creationDate DESC');
        $result = $stmt->execute(['clientId' => $clientId]);
        if ($result !== false && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $loadedConversation = new Conversation();
                $loadedConversation->id = $row['id'];
                $loadedConversation->clientId = $row['clientId'];
                $loadedConversation->supportId = $row['supportId'];
                $loadedConversation->subject = $row['subject'];
                $loadedConversation->creationDate = $row['creationDate'];
                $ret[] = $loadedConversation;
            }
            return $ret;
        }
        return null;
    }

    static public function loadAllConversationsBySupportId(PDO $conn, $supportId){
        $ret = [];
        $stmt = $conn->prepare('SELECT * FROM conversations WHERE supportId=:supportId ORDER BY creationDate DESC');
        $result = $stmt->execute(['supportId' => $supportId]);
        if ($result !== false && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $loadedConversation = new Conversation();
                $loadedConversation->id = $row['id'];
                $loadedConversation->clientId = $row['clientId'];
                $loadedConversation->supportId = $row['supportId'];
                $loadedConversation->subject = $row['subject'];
                $loadedConversation->creationDate = $row['creationDate'];
                $ret[] = $loadedConversation;
            }
            return $ret;
        }
        return null;
    }

    static public function loadOpenConversations(PDO $conn){
        $ret = [];
        $result = $conn->query('SELECT * FROM conversations WHERE supportId IS NULL ORDER BY creationDate DESC');
        if ($result !== false && $result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $loadedConversation = new Conversation();
                $loadedConversation->id = $row['id'];
                $loadedConversation->clientId = $row['clientId'];
                $loadedConversation->supportId = $row['supportId'];
                $loadedConversation->subject = $row['subject'];
                $loadedConversation->creationDate = $row['creationDate'];
                $ret[] = $loadedConversation;
            }
            return $ret;
        }
        return null;
    }

    static public function loadConversationById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM conversations WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedConversation = new Conversation();
            $loadedConversation->id = $row['id'];
            $loadedConversation->clientId = $row['clientId'];
            $loadedConversation->supportId = $row['supportId'];
            $loadedConversation->subject = $row['subject'];
            $loadedConversation->creationDate = $row['creationDate'];
            return $loadedConversation;
        }
        return null;
    }
}