<?php
interface IWebSocketApplication{
    public function onClientConnection(clientConnection $client);
    public function onClientDeconnection(clientConnection $client);
    public function onServerStarted(WebSocketServer $server);
    public function onServerStopped(WebSocketServer $server);
    public function onServerRestart(WebSocketServer $server);
    public function onClientMessage(clientConnection $client,$Message);
}
?>