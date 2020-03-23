<?php
class ServerCommandGroup extends Singleton{
    private $commands = Array();
    private $commandCount = 0;
    function __construct() { parent::__construct(); }
    protected function addCommand(ServerCommand $cmd){
        $this->commands[$this->commandCount] = $cmd;
        $this->commandCount++;
    }
    public function getCount(){ return $this->commandCount; }
    public function call($cmdName,$params){
        foreach($this->commands as $cmd){
            if ($cmd->getName() == $cmdName) {
                $cmd->call($params);
                return true;
            }
        }
        return false;
    }
}
?>