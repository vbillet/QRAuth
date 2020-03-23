<?php
/**
 * cette classe permet de définir une application NetObject.
 * Les objets NetObject sont des objets dont les instances existent en même temps 
 * sur le serveur et sur le client.
 * Les applications NetObjectApplication bénéficient en outre d'un sytème de commande
 */
class NetObjectServerApplication extends WebSocketApplication implements IWebSocketApplication {
    protected static $instance = null;
    protected $commands = null;
    function __construct(){
        parent::__construct();
        $this->commands = new SimGroup();
        $this->commands->setName("Commands");
        /**
         * Exemple de code à mettre en initialisation d'un NetObjectApplication
         * console::get();
         * WebSocketApplicationConnection::get()->RegisterApplication($this);
         * WebSocketServer::get();
         */
    }
    public function onClientConnection(clientConnection $client){
        /**
         * Exemple de code onClientConnection
         * console::Log("[ChatServer] : ClientConnection : ".$client->getIP()." : ".$client->getPort());
         */
    }
    public function onClientDeconnection(clientConnection $client) {
        /**
         * Exemple de code onClientDeconnection
         * console::Log("[ChatServer] : ClientDeconnection : ".$client->getIP()." : ".$client->getPort());
         */
    }
    public function onServerStarted(WebSocketServer $server){
        /**
         * Exemple de code onServerStarted
         * console::Log("[ChatServer] : onServerStarted");
         */
    }
    public function onServerStopped(WebSocketServer $server){
        /**
         * Exemple de code onServerStopped
         * console::Log("[ChatServer] : onServerStopped");
         */
    }
    public function onServerRestart(WebSocketServer $server){
        /**
         * Exemple de code onServerRestart
         * console::Log("[ChatServer] : onServerRestart");
         */
    }
    public function onClientMessage(clientConnection $client,$Message) {
        console::Log("[ChatServer] : onClientMessage");
        // COMMAND¤Param1¤Param2¤param3 ....
        // Ex : Login¤UserName¤PassWord
        // Ex : CheckTel¤0612345678
        // Ex : CheckCode¤code
        // Ex : Add¤Prospect
        $kind = $this->getMessageKind($Message);
        $msg = $this->getMessage($Message);
        if ($kind=="C"){ $this->login($client,$msg); } else
        if ($kind=="O"){ $this->recieveMessage($client,$msg); }
    }
    protected function send($client,$Message){
        WebSocketServer::send($client,$Message);
    }
    protected function disconnect(clientConnection $client){
        WebSocketServer::disconnect($client);
    }
    protected function broadcast($msg){
        WebSocketServer::broadcast($msg);
    }
    private function getMessageKind($msg) { return substr($msg,0,1); }
    private function getMessage($msg) { return substr($msg,1);}
    public function registerCommands(netObjectServerCommand $cmds) {
        $this->commands = $cmds;
    }
}
ChatServer::get();
?>