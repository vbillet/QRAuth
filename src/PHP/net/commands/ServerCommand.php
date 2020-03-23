<?php
class ServerCommand {
    protected $commandName = "";
    protected $paramCount = "";
    protected $func = "";
    function __construct($Name,$count,$fun)
    {
        $this->commandName = $Name;
        $this->paramCount = $count;
        $this->func = $fun;
    }
    public function call($params){
        if ($this->paramCount != count($params)) {
            console::Error("Nombre de paramètres incorrects pour la commande : ".$this->commandName." ".$this->paramCount." attendus, ".count($params)."fournis.");
            return;
        }
        $call = $this->func."(";
        foreach($params as $param){
            $param = str_replace("\"","&quote;",$param);
            $call.="\"".$param."\",";
        }
        $call = substr($call,0,-1);
        $call.= ");";
        eval($call);
    }
    public function getName() {
        return $this->commandName;
    }
}
?>