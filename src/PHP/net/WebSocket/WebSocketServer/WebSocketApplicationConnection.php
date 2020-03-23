<?php
class WebSocketApplicationConnection extends Singleton{
    protected static $instance = null;
    private $appInstance = null;
    function __construct(){ parent::__construct(); }
    public function onClientConnection(clientConnection $client){
        if($this->appInstance != null){ 
            $this->appInstance->onClientConnection($client);
        }
    }
    public function onClientDeconnection(clientConnection $client){
        if($this->appInstance != null){ 
            $this->appInstance->onClientDeconnection($client);
        }
    }
    public function onServerStarted(WebSocketServer $server){
        if($this->appInstance != null){ 
            $this->appInstance->onServerStarted($server);
        }
    }
    public function onServerStopped(WebSocketServer $server){
        if($this->appInstance != null){ 
            $this->appInstance->onServerStopped($server);
        }
    }
    public function onServerRestart(WebSocketServer $server){
        if($this->appInstance != null){ 
            $this->appInstance->onServerRestart($server);
        }
    }
    public function onClientMessage(clientConnection $client,$Message){
        if($this->appInstance != null){ 
            $this->appInstance->onClientMessage($client,$Message);
        }
    }
    public function RegisterApplication(WebSocketApplication $app){
        if ($app!=null) $this->appInstance = $app;
    }
}
?>