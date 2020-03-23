<?php
class clientConnection extends SimObject{
    private $ip=null;
    private $port=null;
    private $socket = null;
    public static $CCConntecting = 0;
    public static $CCSendData = 1;
    public static $CCSendServerGroup = 2;
    public static $CCConnected = 3;
    public static $CCMax = 4;
    private $stage = 0;
    public function __construct($socket,$ip,$port){
        parent::__construct();
        $this->ip = $ip;
        $this->port = $port;
        $this->socket = $socket;
    }
    public function getIP() { return $this->ip; }
    public function getPort() { return $this->port; }
    public function getSocket() { return $this->socket; }
    public function setStage(int $stage) {
        if (($stage<$this->stage) || ($stage>clientConnection::$CCMax))
            return;
        $this->stage = $stage;
    }
    public function getStage() { return $this->stage; }
    public function getClientInfo() {
        return "IP : ".$this->ip." Port : ".$this->port." Socket : ".(int)$this->socket;
    }
}
?>