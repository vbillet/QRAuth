<?php
abstract class WebSocketApplication extends Singleton implements IWebSocketApplication {
    // Pas d'instance : Ne doit pas être instanciée directement.
    public function __construct() {
        parent::__construct();
    }
    abstract public function onClientConnection(clientConnection $client);
    abstract public function onClientDeconnection(clientConnection $client);
    abstract public function onServerStarted(WebSocketServer $server);
    abstract public function onServerStopped(WebSocketServer $server);
    abstract public function onServerRestart(WebSocketServer $server);
    abstract public function onClientMessage(clientConnection $client,$Message);
}
?>