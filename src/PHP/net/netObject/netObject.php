<?php
FieldsManager::BEGIN_FIELDS_DECLARATION("NetObject","SimGroup");
FieldsManager::END_FIELDS_DECLARATION();

class NetObject extends SimGroup {
    protected $ghost = null;
    private $isServer = true;
    function __construct(){
        parent::__construct();
    }
    public function setGhostAlways() { $this->ghost = null; }
    public function setGhostClient(clientConnection $client) { $this->ghost = $client; }
    public function getGhosting() { return $this->ghost; }
    public function __set(){
        
    }
    public function pack(){

    }
    public function unpack(){

    }
    public function setDirty(){

    }
    public function clearDirty(){

    }
    public function isServer(){
        return console::isServer();
    }
    public function isClient() {
        return !console::isServer();
    }
}
?>