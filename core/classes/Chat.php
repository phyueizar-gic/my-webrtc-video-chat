<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;
    private $userObj, $userData;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->userObj = new \MyApp\User;
    }

    public function onOpen(ConnectionInterface $conn) {
        $queryString = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryString, $query);
        if($data = $this->userObj->getUserBySessionID($query['token'])) {
            $this->userData = $data;
            $conn->userData = $data;
            $conn->send(json_encode(array(
                "type" => "CONNECTION_ESTABLISHED",
                "status" => "Online"
            )));

            foreach($this->clients as $client) {
                $client->send(json_encode(array(
                    "type" => "NEW_USER_CONNECTION",
                    "status" => "Online",
                    "fullName" => $data->firstName . ' ' . $data->lastName,
                    "profileImage" => $data->profileImage,
                    "userID" => $data->userID
                )));
            }

            // Store the new connection to send messages to later
            $this->clients->attach($conn);
            $this->userObj->updateConnection($conn->resourceId, 'Online', $data->userID);

            echo "New connection! ({$data->username})\n";
        }
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        
        $data = json_decode($msg, true);
        $type = $data['type'];
        switch($type){
            case "client-is-ready":
                foreach ($this->clients as $client) {
                    if ($from == $client) {
                        // The sender is the receiver, send to client connected
                        $client->send(json_encode(array(
                            "type" => "client-is-ready",
                            "success" => true
                        )));
                    }
                }
                break;
            case "offer":
                $receiverData = $this->userObj->userData($data['target']);
                foreach ($this->clients as $client) {
                    if ($from !== $client) {
                        if($client->resourceId == $receiverData->connectionID || $from == $client) {
                            // The sender is not the receiver, send to each client connected
                             $client->send(json_encode(array(
                                "type" => $data['type'],
                                "offer" => $data['data'],
                                "sender" => $from->userData->userID,
                                "receiver" => $data['target'],
                                "name" => $from->userData->firstName . ' ' . $from->userData->lastName,
                                "profileImage" => $from->userData->profileImage
                            )));
                        }
                    }
                }
                break;
            case "answer":
                $receiverData = $this->userObj->userData($data['target']);
                foreach ($this->clients as $client) {
                    if ($from !== $client) {
                        if($client->resourceId == $receiverData->connectionID || $from == $client) {
                            // The sender is not the receiver, send to each client connected
                            $client->send(json_encode(array(
                                "type" => $data['type'],
                                "answer" => $data['data'],
                                "sender" => $from->userData->userID,
                                "receiver" => $data['target']
                            )));
                        }
                    }
                }
                break;
            case "candidate":
                $receiverData = $this->userObj->userData($data['target']);
                foreach ($this->clients as $client) {
                    if ($from !== $client) {
                        if($client->resourceId == $receiverData->connectionID || $from == $client) {
                            // The sender is not the receiver, send to each client connected
                            $client->send(json_encode(array(
                                "type" => $data['type'],
                                "candidate" => $data['data'],
                                "sender" => $from->userData->userID,
                                "receiver" => $data['target']
                            )));
                        }
                    }
                }
                break;
            case "reject":
                $receiverData = $this->userObj->userData($data['target']);
                foreach ($this->clients as $client) {
                    if ($from !== $client) {
                        if($client->resourceId == $receiverData->connectionID || $from == $client) {
                            // The sender is not the receiver, send to each client connected
                            $client->send(json_encode(array(
                                "type" => $data['type'],
                                "reject" => $data['data'],
                                "sender" => $from->userData->userID,
                                "receiver" => $data['target']
                            )));
                        }
                    }
                }
                break;
            case "accept":
                $receiverData = $this->userObj->userData($data['target']);
                foreach ($this->clients as $client) {
                    if ($from !== $client) {
                        if($client->resourceId == $receiverData->connectionID || $from == $client) {
                            // The sender is not the receiver, send to each client connected
                            $client->send(json_encode(array(
                                "type" => $data['type'],
                                "accept" => $data['data'],
                                "sender" => $from->userData->userID,
                                "receiver" => $data['target']
                            )));
                        }
                    }
                }
                break;
            case "leave":
                $receiverData = $this->userObj->userData($data['target']);
                foreach ($this->clients as $client) {
                    if ($from !== $client) {
                        if($client->resourceId == $receiverData->connectionID || $from == $client) {
                            // The sender is not the receiver, send to each client connected
                            $client->send(json_encode(array(
                                "type" => $data['type'],
                                "leave" => $data['data'],
                                "sender" => $from->userData->userID,
                                "receiver" => $data['target']
                            )));
                        }
                    }
                }
                break;    
        }
        // foreach ($this->clients as $client) {
        //     if ($from !== $client) {
        //         // The sender is not the receiver, send to each client connected
        //         $client->send($msg);
        //     }
        // }
    }

    public function onClose(ConnectionInterface $conn) {
        $queryString = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryString, $query);
        if($data = $this->userObj->getUserBySessionID($query['token'])) {
            $this->userData = $data;
            $conn->userData = $data;
            $conn->send(json_encode(array(
                "type" => "CONNECTION_DISCONNECTED",
                "status" => "Offline"
            )));

            foreach($this->clients as $client) {
                $client->send(json_encode(array(
                    "type" => "NEW_USER_DISCONNECTED",
                    "status" => "Offline",
                    "fullName" => $data->firstName . ' ' . $data->lastName,
                    "profileImage" => $data->profileImage,
                    "userID" => $data->userID
                )));
            }
            
            $this->userObj->updateConnection($conn->resourceId, 'Offline', $data->userID);

            // The connection is closed, remove it, as we can no longer send it messages
            $this->clients->detach($conn);

            echo "Connection {$conn->resourceId} has disconnected\n";
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
