<?php
class WebSocketServer extends Singleton {
    protected static $instance = null;
    /**
     * @var array $sockets
     */
    //private $sockets = [];
    private $socketServer;
    private $socketList = [];
    private $accepted = [];
    private $clientGroup = null;
    private $serverGroup = null;
    public $crypto = null;
    public function __construct() {
        parent::__construct();
        $host = "192.168.1.9";
        $port = "8080";
        $this->serverGroup = new SimGroup();
        $this->serverGroup->setName("serverGroup");
        $this->clientGroup = new SimGroup();
        $this->clientGroup->setName("clientGroup");
        $this->crypto = new CryptoNone();
        $this->createServer($host,$port);
        $this->runServer();
    }
    private function showSocketError($msg){
        $err_code = socket_last_error();
        $err_msg = socket_strerror($err_code);
        console::Error($msg." : ". $err_code." : ".$err_msg);
    }
    private function checkSocketError($chk,$msg){
        if (false === $chk)
            $this->showSocketError($msg);
    }
    private function createServer($host,$port){
        try {
            $this->socketServer = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_set_option($this->socketServer, SOL_SOCKET, SO_REUSEADDR, 1);
            socket_bind($this->socketServer, $host, $port);
            socket_listen($this->socketServer, Config::getCLIServerMaxClient());
        } catch (Exception $e) {
            $this->showSocketError("error_init_server");
        }
        $this->socketList[0] = $this->socketServer;
        $pid = getmypid();
        console::Log("SERVER : ".$host." : ".$port." started, pid: ".$pid);
        console::get()->setServer();
        WebSocketApplicationConnection::get()->onServerStarted($this);
    }

    private function runServer(){
        while (true) {
            try {
                $this->doServer();
            } catch (Exception $e) {
                console::Error("error_do_server :".$e->getCode()." : ".$e->getMessage());
            }
        }
    }

    private function doServer() {
        $write = $except = NULL;
        $sockets = $this->socketList;

        $read = socket_select($sockets, $write, $except, NULL);
        $this->checkSocketError($read,"Error select");

        if ($read !== false)
            foreach ($sockets as $socket) 
                if ($socket == $this->socketServer) 
                    $this->acceptClientConnection();
                else 
                    $this->recieveFromClient($socket);
    }

    private function acceptClientConnection(){
        $client = socket_accept($this->socketServer);
        $this->checkSocketError($client, "Error accept");
        if (false !== $client) 
            $this->socketList[(int)$client] = $client;

    }

    private function recieveFromClient($socket){
        $bytes = @socket_recv($socket, $buffer, 2048, 0);

        if ($bytes < 9) {
            $this->_disconnect($socket);
        } else {
            if (!isset($this->accepted[(int)$socket])) {
                $this->handShake($socket, $buffer);
            } else {
                $this->recieve($this->accepted[(int)$socket], $buffer);
            }
        }
    }
    private function _disconnect($socket) {
        socket_getpeername($socket, $ip, $port);
        unset($this->socketList[(int)$socket]);
        if (isset($this->accepted[(int)$socket]))
        {
            WebSocketApplicationConnection::get()->onClientDeconnection($this->accepted[(int)$socket]);
            $this->accepted[(int)$socket]->Delete();
            unset($this->accepted[(int)$socket]);
        }
        //$this->showClientGroup();
    }
    
    public static function disconnect(clientConnection $client){
        WebSocketServer::get()->_disconnect($client->getSocket());
    }

    private function handShake($socket, $buffer) {
        // Création de l'objet clientConnection
        socket_getpeername($socket, $ip, $port);
        $this->accepted[(int)$socket] = new clientConnection($socket,$ip,$port);
        $this->clientGroup->add($this->accepted[(int)$socket]);

        $line_with_key = substr($buffer, strpos($buffer, 'Sec-WebSocket-Key:') + 18);
        $key = trim(substr($line_with_key, 0, strpos($line_with_key, "\r\n")));
        if ($key=="")
        {
            $upgrade_message = "HTTP/1.1 400 Invalid Request\r\n\r\n";
            socket_write($socket, $upgrade_message, strlen($upgrade_message));
            $this->_disconnect($socket);
            return false;
        }
        $upgrade_key = base64_encode(sha1($key . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11", true));
        $upgrade_message = "HTTP/1.1 101 Switching Protocols\r\n";
        $upgrade_message .= "Upgrade: websocket\r\n";
        $upgrade_message .= "Sec-WebSocket-Version: 13\r\n";
        $upgrade_message .= "Connection: Upgrade\r\n";
        $upgrade_message .= "Sec-WebSocket-Accept:" . $upgrade_key . "\r\n\r\n";
        socket_write($socket, $upgrade_message, strlen($upgrade_message));
        WebSocketApplicationConnection::get()->onClientConnection($this->accepted[(int)$socket]);
        return true;
    }

    private function recieve(clientConnection $client, $buffer){
        $msg = $this->parse($buffer);
        WebSocketApplicationConnection::get()->onClientMessage($client,$msg);
    }

    private function parse($buffer) {
        $decoded = '';
        $len = ord($buffer[1]) & 127;
        if ($len === 126) {
            $masks = substr($buffer, 4, 4);
            $data = substr($buffer, 8);
        } else if ($len === 127) {
            $masks = substr($buffer, 10, 4);
            $data = substr($buffer, 14);
        } else {
            $masks = substr($buffer, 2, 4);
            $data = substr($buffer, 6);
        }
        for ($index = 0; $index < strlen($data); $index++) {
            $decoded .= $data[$index] ^ $masks[$index % 4];
        }
        $crypto = WebSocketServer::getCryptographicProtocol();
        $decoded = $crypto->decrypt($decoded);
        return $decoded;
    }

    public static function send(clientConnection $client,$message){
        $msg = WebSocketServer::build($message);
        socket_write($client->getSocket(), $msg, strlen($msg));
    }
    public function _broadcast($msg){
        foreach($this->socketList as $socket){
            if ($socket != $this->socketServer)
                socket_write($socket, $msg, strlen($msg));
        }
    }
    public static function broadcast($message){
        $msg = WebSocketServer::build($message);
        WebSocketServer::get()->_broadcast($msg);
    }
    private static function build($msg) {
        $frame = [];
        $frame[0] = '81';
        $len = strlen($msg);
        if ($len < 126) {
            $frame[1] = $len < 16 ? '0' . dechex($len) : dechex($len);
        } else if ($len < 65025) {
            $s = dechex($len);
            $frame[1] = '7e' . str_repeat('0', 4 - strlen($s)) . $s;
        } else {
            $s = dechex($len);
            $frame[1] = '7f' . str_repeat('0', 16 - strlen($s)) . $s;
        }
        $data = '';
        $crypto = WebSocketServer::getCryptographicProtocol();
        $msg = $crypto->crypt($msg);
        $l = strlen($msg);
        for ($i = 0; $i < $l; $i++) {
            $data .= dechex(ord($msg{$i}));
        }
        $frame[2] = $data;
        $data = implode('', $frame);
        $ch = pack("H*", $data);
//        console::Log(strlen($data)." ".$data);
//        console::Log(strlen($ch)." ".$ch);
        return $ch;
    }
    public function setCryptographicProtocol($crypto){
        try{
            if ($crypto->isCrypto()){
                $this->crypto = $crypto;
            }
        } catch (Exception $e){
            console::Error("Invalid cryptographic object");
        }
    }
    public static function getCryptographicProtocol(){
        return WebSocketServer::get()->crypto;
    }
    public function getServerGroup() { return WebSocketServer::get()->serverGroup; }
    public function getClientGroup() { return WebSocketServer::get()->clientGroup; }
}
?>