<?php
class Module extends Singleton implements IModule {
    protected $commandGroup = null;
    protected $Events = Array();
    protected static $server = null;

    protected function setCommandGroup($cmdGroup) {
        $this->commandGroup = $cmdGroup;
    }
    public function callCommand($command,$params) {
        return $this->commandGroup->call($command,$params);
    }
    protected function AddEventListener($event,$cmd) {
        if (strpos($cmd,"::")>0){
            list($obj,$meth) = explode("::",$cmd);
            if (method_exists($obj,$meth)){
                $this->Events[$event] = $cmd;
            } else {
                console::Error("Method ".$cmd." does not exists. Can't register event ".$event);
            }
        } else{
            console::Error($cmd." is invalid. It must be static. Can't register event ".$event);
        }
    }
    public function processEvent($event,$params) {
        $objectIndex = 0;
        $objects = Array();
        foreach($this->Events as $evt=>$func) {
            if ($evt==$event){
                $call = "try { ".$func."(";
                foreach($params as $param){
                    if (is_object($param)) {
                        $objects[$objectIndex] = $param;
                        $call.="\$objects[".$objectIndex."],";
                        $objectIndex++;
                    } else {
                        $param = str_replace("\"","&quote;",$param);
                        $call.="\"".$param."\",";
                    }
                }
                $call = substr($call,0,-1);
                $call.= "); } catch (Exception \$e) { console::Error('Unable to process event ".$func."'); }";
                console::Log($call);
                try {
                    eval($call);
                } catch (Exception $e) {
                    console::Log($e->message." : ".$call);
                }
                break;
            }
        }
    }
    public function throwEvent($event,$params) {
        $this->server->doEvent($event,$params);
    }
}
?>