<?php
require_once "simObject.php";

FieldsManager::BEGIN_FIELDS_DECLARATION("SimGroup","SimObject");
FieldsManager::END_FIELDS_DECLARATION();

class SimGroup extends SimObject {
    private $childs=Array();
    function __construct(){
        parent::__construct();
    }
    public function add($sim){
        if (!is_a($sim,"SimObject")) return;
        $parentid = $sim->getParent();
        if ( $parentid != null);
        {
            $parent = console::getById($parentid);
            if ($parent != null){
                $parent->remove($sim);
            }
        }
        $sim->setParent($this);
        $this->childs[$sim->getID()] = $sim;
    }
    protected function remove($sim) { unset($this->childs[$sim->getId()]); }
    public function count() { return sizeof($this->childs); }
    public function getChilds() { return $this->childs; }
    public function toXML() {
        $vars = get_object_vars($this);
        $result = "<".get_called_class();
        foreach ($vars as $name=>$value)
        {
            $field = FieldsManager::getField(get_called_class(),$name);
            if(($value!="") && ($value!=null) && ($field!=null))
            {
                //$field->set($value);
                $result.= " ". $name."=\"".$value."\"";
            }
        }
        $result.=">";
        foreach($this->childs as $child)
        {
            $result.=$child->toXML();
        }
        $result.="</".get_called_class().">";
        return $result;
    }
}
?>